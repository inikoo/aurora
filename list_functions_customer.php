<?php


function customers_awhere($awhere) {
	// $awhere=preg_replace('/\\\"/','"',$awhere);
	//    print "$awhere";


	$where_data=array(
		'product_ordered1'=>'∀',
		'geo_constraints'=>'',
		'product_not_ordered1'=>'',
		'product_not_received1'=>'',
		'ordered_from'=>'',
		'ordered_to'=>'',
		'customer_created_from'=>'',
		'customer_created_to'=>'',
		'dont_have'=>array(),
		'have'=>array(),
		'allow'=>array(),
		'dont_allow'=>array(),
		'customers_which'=>array(),
		'not_customers_which'=>array(),
		'categories'=>'',
		'lost_customer_from'=>'',
		'lost_customer_to'=>'',
		'invoice_option'=>array(),
		'number_of_invoices_upper'=>'',
		'number_of_invoices_lower'=>'',
		'sales_lower'=>'',
		'sales_upper'=>'',
		'sales_option'=>array(),
		'logins_lower'=>'',
		'logins_upper'=>'',
		'logins_option'=>array(),

		'failed_logins_lower'=>'',
		'failed_logins_upper'=>'',
		'failed_logins_option'=>array(),

		'requests_lower'=>'',
		'requests_upper'=>'',
		'requests_option'=>array(),
		'store_key'=>0,
		'order_option'=>array(),
		'order_time_units_since_last_order_qty'=>false,
		'order_time_units_since_last_order_units'=>false
	);

	//  $awhere=json_decode($awhere,TRUE);


	foreach ($awhere as $key=>$item) {
		$where_data[$key]=$item;
	}




	$where=sprintf('where  `Customer Store Key`=%d ',$where_data['store_key']);
	$table='`Customer Dimension` C ';

	//print_r($where_data);
	$use_product=false;
	$use_categories =false;
	$use_otf =false;

	$where_categories='';
	if ($where_data['categories']!='') {

		$categories_keys=preg_split('/,/',$where_data['categories']);
		$valid_categories_keys=array();
		foreach ($categories_keys as $item) {
			if (is_numeric($item))
				$valid_categories_keys[]=$item;
		}
		$categories_keys=join($valid_categories_keys,',');
		if ($categories_keys) {
			$use_categories =true;
			$where_categories=sprintf(" and `Subject`='Customer' and `Category Key` in (%s)",$categories_keys);
		}


	}

	$where_geo_constraints='';
	if ($where_data['geo_constraints']!='') {
		$where_geo_constraints=extract_customer_geo_groups($where_data['geo_constraints']);
	}


	if ($where_data['product_ordered1']=='')
		$where_data['product_ordered1']='∀';


	if ($where_data['product_ordered1']!='') {
		if ($where_data['product_ordered1']!='∀') {
			$use_otf=true;
			list($where_product_ordered1,$use_product)=extract_product_groups($where_data['product_ordered1'],$where_data['store_key']);
		} else
			$where_product_ordered1='true';
	} else {
		$where_product_ordered1='false';
	}

	/*
        if ($where_data['product_not_ordered1']!='') {
            if ($where_data['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($where_data['product_ordered1'],'O.`Product Code` not like','transaction.product_id not like','OTF.`Product Family Key` not in ','O.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($where_data['product_not_received1']!='') {
            if ($where_data['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($where_data['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

    */


	$date_interval_when_ordered=prepare_mysql_dates($where_data['ordered_from'],$where_data['ordered_to'],'`Order Date`','only_dates');
	if ($date_interval_when_ordered['mysql']) {
		$use_otf=true;
	}



	$date_interval_when_customer_created=prepare_mysql_dates($where_data['customer_created_from'],$where_data['customer_created_to'],'`Customer First Contacted Date`','only_dates');
	if ($date_interval_when_customer_created['mysql']) {

	}

	$date_interval_lost_customer=prepare_mysql_dates($where_data['lost_customer_from'],$where_data['lost_customer_to'],'`Customer Lost Date`','only_dates');




	if ($use_otf) {
		$table='`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
	}
	if ($use_product) {
		$table='`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`) left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  ';
	}



	if ($use_categories) {

		$table.='  left join   `Category Bridge` CatB on (C.`Customer Key`=CatB.`Subject Key`)   ';
	}




	$where.=' and (  '.$where_product_ordered1.$date_interval_when_ordered['mysql'].$date_interval_when_customer_created['mysql'].$date_interval_lost_customer['mysql'].") $where_categories $where_geo_constraints";

	foreach ($where_data['dont_have'] as $dont_have) {
		switch ($dont_have) {
		case 'tel':
			$where.=sprintf(" and `Customer Main Telephone Key` IS NULL ");
			break;
		case 'email':
			$where.=sprintf(" and `Customer Main Email Key` IS NULL ");
			break;
		case 'fax':
			$where.=sprintf(" and `Customer Main Fax Key` IS NULL ");
			break;
		case 'address':
			$where.=sprintf(" and `Customer Main Address Incomplete`='Yes' ");
			break;
		}
	}
	foreach ($where_data['have'] as $dont_have) {
		switch ($dont_have) {
		case 'tel':
			$where.=sprintf(" and `Customer Main Telephone Key` IS NOT NULL ");
			break;
		case 'email':
			$where.=sprintf(" and `Customer Main Email Key` IS NOT NULL ");
			break;
		case 'fax':
			$where.=sprintf(" and `Customer Main Fax Key` IS NOT NULL ");
			break;
		case 'address':
			$where.=sprintf(" and `Customer Main Address Incomplete`='No' ");
			break;
		}
	}

	$allow_where='';
	foreach ($where_data['allow'] as $allow) {
		switch ($allow) {
		case 'newsletter':
			$allow_where.=sprintf(" or `Customer Send Newsletter`='Yes' ");
			break;
		case 'marketing_email':
			$allow_where.=sprintf(" or `Customer Send Email Marketing`='Yes'  ");
			break;
		case 'marketing_post':
			$allow_where.=sprintf(" or  `Customer Send Postal Marketing`='Yes'  ");
			break;

		}



	}
	$allow_where=preg_replace('/^\s*or/','',$allow_where);
	if ($allow_where!='') {
		$where.="and ($allow_where)";
	}

	$dont_allow_where='';
	foreach ($where_data['dont_allow'] as $dont_allow) {
		switch ($dont_allow) {
		case 'newsletter':
			$dont_allow_where.=sprintf(" or `Customer Send Newsletter`='No' ");
			break;
		case 'marketing_email':
			$dont_allow_where.=sprintf(" or `Customer Send Email Marketing`='No'  ");
			break;
		case 'marketing_post':
			$dont_allow_where.=sprintf(" or  `Customer Send Postal Marketing`='No'  ");
			break;
		}



	}
	$dont_allow_where=preg_replace('/^\s*or/','',$dont_allow_where);
	if ($dont_allow_where!='') {
		$where.="and ($dont_allow_where)";
	}


	$customers_which_where='';
	foreach ($where_data['customers_which'] as $customers_which) {
		switch ($customers_which) {
		case 'active':
			$customers_which_where.=sprintf(" or `Customer Active`='Yes' ");
			break;
		case 'losing':
			$customers_which_where.=sprintf(" or `Customer Type by Activity`='Losing'  ");
			break;
		case 'lost':
			$customers_which_where.=sprintf(" or `Customer Active`='No'  ");
			break;
		}
	}
	$customers_which_where=preg_replace('/^\s*or/','',$customers_which_where);
	if ($customers_which_where!='') {
		$where.="and ($customers_which_where)";
	}

	//print_r($where_data);
	if ($where_data['order_time_units_since_last_order_qty']>0) {

		switch ($where_data['order_time_units_since_last_order_units']) {
		case 'days':

			$_date=date("Y-m-d 00:00:00",strtotime(sprintf("today -%d %s",$where_data['order_time_units_since_last_order_qty'],$where_data['order_time_units_since_last_order_units'])));

			$where.=sprintf(' and `Customer Last Order Date`>=%s ',prepare_mysql($_date));
			break;
		default:

			break;
		}

	}




	$invoice_option_where='';
	foreach ($where_data['invoice_option'] as $invoice_option) {
		switch ($invoice_option) {
		case 'less':
			if (is_numeric($where_data['number_of_invoices_lower']))
				$invoice_option_where.=sprintf(" and `Customer Orders Invoiced`<'%d' ",$where_data['number_of_invoices_lower']);
			break;
		case 'equal':
			if (is_numeric($where_data['number_of_invoices_lower']))
				$invoice_option_where.=sprintf(" and `Customer Orders Invoiced`='%d'  ",$where_data['number_of_invoices_lower']);
			break;
		case 'more':
			if (is_numeric($where_data['number_of_invoices_lower']))
				$invoice_option_where.=sprintf(" and  `Customer Orders Invoiced`>'%d'  ",$where_data['number_of_invoices_lower']);
			break;

		case 'between':
			if (is_numeric($where_data['number_of_invoices_lower']))
				$invoice_option_where.=sprintf(" and `Customer Orders Invoiced`>='%d' ",$where_data['number_of_invoices_lower']);
			if (is_numeric($where_data['number_of_invoices_upper']))
				$invoice_option_where.=sprintf(" and `Customer Orders Invoiced`<='%d' ",$where_data['number_of_invoices_upper']);

			break;
		}
	}
	$invoice_option_where=preg_replace('/^\s*and/','',$invoice_option_where);

	if ($invoice_option_where!='') {
		$where.="and ($invoice_option_where)";
	}

	$order_option_where='';
	foreach ($where_data['order_option'] as $order_option) {
		switch ($order_option) {
		case 'less':
			if (is_numeric($where_data['number_of_orders_lower']))
				$order_option_where.=sprintf(" and `Customer Orders`<'%d' ",$where_data['number_of_orders_lower']);
			break;
		case 'equal':
			if (is_numeric($where_data['number_of_orders_lower']))
				$order_option_where.=sprintf(" and `Customer Orders`='%d'  ",$where_data['number_of_orders_lower']);
			break;
		case 'more':
			if (is_numeric($where_data['number_of_orders_lower']))
				$order_option_where.=sprintf(" and  `Customer Orders`>'%d'  ",$where_data['number_of_orders_lower']);
			break;

		case 'between':
			if (is_numeric($where_data['number_of_orders_lower']))
				$order_option_where.=sprintf(" and `Customer Orders`>='%d' ",$where_data['number_of_orders_lower']);
			if (is_numeric($where_data['number_of_orders_upper']))
				$order_option_where.=sprintf(" and `Customer Orders`<='%d' ",$where_data['number_of_orders_upper']);

			break;
		}
	}





	$order_option_where=preg_replace('/^\s*and/','',$order_option_where);






	if ($order_option_where!='') {
		$where.="and ($order_option_where)";
	}

	//print_r($where_data);
	$sales_option_where='';
	foreach ($where_data['sales_option'] as $sales_option) {
		switch ($sales_option) {
		case 'sales_less':
			if (is_numeric($where_data['sales_lower']))
				$sales_option_where.=sprintf(" and `Customer Net Payments`<'%f' ",$where_data['sales_lower']);
			break;
		case 'sales_equal':
			if (is_numeric($where_data['sales_lower']))
				$sales_option_where.=sprintf(" and `Customer Net Payments`='%f'  ",$where_data['sales_lower']);
			break;
		case 'sales_more':
			if (is_numeric($where_data['sales_lower']))
				$sales_option_where.=sprintf(" and  `Customer Net Payments`>'%f'  ",$where_data['sales_lower']);
			break;

		case 'sales_between':
			if (is_numeric($where_data['sales_lower']))
				$sales_option_where.=sprintf(" and  `Customer Net Payments`>='%f'  ",$where_data['sales_lower']);
			if (is_numeric($where_data['sales_upper']))
				$sales_option_where.=sprintf(" and  `Customer Net Payments`<='%f'  ",$where_data['sales_upper']);
			break;
		}
	}
	$sales_option_where=preg_replace('/^\s*and/','',$sales_option_where);
	if ($sales_option_where!='') {
		$where.="and ($sales_option_where)";
	}



	$logins_option_where='';
	foreach ($where_data['logins_option'] as $logins_option) {
		switch ($logins_option) {
		case 'logins_less':
			if (is_numeric($where_data['logins_lower']))
				$logins_option_where.=sprintf(" and `Customer Number Web Logins`<%d ",$where_data['logins_lower']);
			break;
		case 'logins_equal':
			if (is_numeric($where_data['logins_lower']))
				$logins_option_where.=sprintf(" and `Customer Number Web Logins`=%d  ",$where_data['logins_lower']);
			break;
		case 'logins_more':
			if (is_numeric($where_data['logins_lower']))
				$logins_option_where.=sprintf(" and  `Customer Number Web Logins`>%d  ",$where_data['logins_lower']);
			break;
		case 'logins_between':
			if (is_numeric($where_data['logins_lower']))
				$logins_option_where.=sprintf(" and  `Customer Number Web Logins`>=%d  ",$where_data['logins_lower']);
			if (is_numeric($where_data['logins_upper']))
				$logins_option_where.=sprintf(" and  `Customer Number Web Logins`<=%d  ",$where_data['logins_upper']);


			break;
		}
	}
	$logins_option_where=preg_replace('/^\s*and/','',$logins_option_where);
	if ($logins_option_where!='') {
		$where.="and ($logins_option_where)";
	}

	$failed_logins_option_where='';
	foreach ($where_data['failed_logins_option'] as $failed_logins_option) {
		switch ($failed_logins_option) {
		case 'failed_logins_less':
			if (is_numeric($where_data['failed_logins_lower']))

				$failed_logins_option_where.=sprintf(" and `Customer Number Web Failed Logins`<%d ",$where_data['failed_logins_lower']);
			break;
		case 'failed_logins_equal':
			if (is_numeric($where_data['failed_logins_lower']))
				$failed_logins_option_where.=sprintf(" and `Customer Number Web Failed Logins`=%d  ",$where_data['failed_logins_lower']);
			break;
		case 'failed_logins_more':
			if (is_numeric($where_data['failed_logins_lower']))
				$failed_logins_option_where.=sprintf(" and  `Customer Number Web Failed Logins`>%d  ",$where_data['failed_logins_lower']);
			break;
		case 'failed_logins_between':
			if (is_numeric($where_data['failed_logins_lower']))
				$failed_logins_option_where.=sprintf(" and `Customer Number Web Failed Logins`>=%d ",$where_data['failed_logins_lower']);
			if (is_numeric($where_data['failed_logins_upper']))
				$failed_logins_option_where.=sprintf(" and `Customer Number Web Failed Logins`<=%d ",$where_data['failed_logins_upper']);



			break;
		}
	}
	$failed_logins_option_where=preg_replace('/^\s*and/','',$failed_logins_option_where);
	if ($failed_logins_option_where!='') {
		$where.="and ($failed_logins_option_where)";
	}

	$requests_option_where='';
	foreach ($where_data['requests_option'] as $requests_option) {
		switch ($requests_option) {
		case 'requests_less':
			if (is_numeric($where_data['requests_lower']))

				$requests_option_where.=sprintf(" and `Customer Number Web Requests`<%d ",$where_data['requests_lower']);
			break;
		case 'requests_equal':
			if (is_numeric($where_data['requests_lower']))
				$requests_option_where.=sprintf(" and `Customer Number Web Requests`=%d  ",$where_data['requests_lower']);
			break;
		case 'requests_more':
			if (is_numeric($where_data['requests_lower']))
				$requests_option_where.=sprintf(" and  `Customer Number Web Requests`>%d  ",$where_data['requests_lower']);
			break;
		case 'requests_between':
			if (is_numeric($where_data['requests_lower']))
				$requests_option_where.=sprintf(" and `Customer Number Web Requests`>-%d ",$where_data['requests_lower']);
			if (is_numeric($where_data['requests_upper']))
				$requests_option_where.=sprintf(" and `Customer Number Web Requests`<=%d ",$where_data['requests_upper']);

			break;
		}
	}
	$requests_option_where=preg_replace('/^\s*and/','',$requests_option_where);
	if ($requests_option_where!='') {
		$where.="and ($requests_option_where)";
	}




	/*
    $not_customers_which_where='';
    foreach($where_data['not_customers_which'] as $not_customers_which) {
        switch ($not_customers_which) {
        case 'active':
            $not_customers_which_where.=sprintf(" or `Customer Active`='No' ");
            break;
        case 'losing':
            $not_customers_which_where.=sprintf(" or `Customer Type by Activity`='Active'  ");
            break;
        case 'lost':
            $not_customers_which_where.=sprintf(" or  `Customer Active`='Yes'  ");
            break;
        }
    }

    $not_customers_which_where=preg_replace('/^\s*or/','',$not_customers_which_where);
    if($not_customers_which_where!=''){
    $where.="and ($not_customers_which_where)";
    }
    *///print $table;print $where;
//	print "$where; <br/>$table "; exit;
	return array($where,$table);


}


?>