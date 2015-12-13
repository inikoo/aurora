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

if (isset($parameters['elements_type'])) {


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

function orders_awhere($awhere) {
	// $awhere=preg_replace('/\\\"/','"',$awhere);



	$where_data=array(
		//'product_ordered1'=>'∀',
		'geo_constraints'=>'',
		'product_not_ordered1'=>'',
		'product_not_received1'=>'',
		'billing_geo_constraints'=>'',
		'delivery_geo_constraints'=>'',
		'dont_have'=>array(),
		'have'=>array(),
		'allow'=>array(),
		'dont_allow'=>array(),
		'categories'=>'',
		'product_ordered_or_from'=>'',
		'product_ordered_or_to'=>'',
		'order_created_from'=>'',
		'order_created_to'=>'',
		'product_ordered_or'=>'',
		'store_key'=>false
	);

	//  $awhere=json_decode($awhere,TRUE);


	foreach ($awhere as $key=>$item) {
		$where_data[$key]=$item;
	}

	$where='where true';
	$table='`Order Dimension` O ';

	$use_product=false;
	//$use_categories =false;
	$use_otf =false;
	/*
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
    */
	$wr=array();
	$country=array();
	$city=array();
	$postal_code=array();


	$where_billing_geo_constraints='';
	print $where_data['billing_geo_constraints'];
	$pattern_wr = array("/^wr\(/", "/\)/");
	$pattern_city = array("/^t\(/", "/\)/");
	$pattern_pc = array("/^pc\(/", "/\)/");
	$pattern_country = '';

	$temp=explode(",", $where_data['billing_geo_constraints']);
	foreach ($temp as $key=>$value) {
		if (preg_match('/^wr\(/', $value))
			$wr[]=preg_replace($pattern_wr, '', $value);
		else if (preg_match('/^t\(/', $value))
			$city[]=preg_replace($pattern_city, '', $value);
		else if (preg_match('/^pc\(/', $value))
			$postal_code[]=preg_replace($pattern_pc, '', $value);
		else
			$country[]=$value;
	}
	//print 'wr';
	//print_r($wr);
	//print 'country';
	//print_r($country);
	//print 'city';
	//print_r($city);
	//print 'pc';
	//print_r($postal_code);
	//exit;
	if ($where_data['billing_geo_constraints']!='') {
		$where_billing_geo_constraints=sprintf(" and `Order Billing To Country 2 Alpha Code`='%s'", $where_data['billing_geo_constraints']);
	}

	$where_delivery_geo_constraints='';
	if ($where_data['delivery_geo_constraints']!='') {
		$where_delivery_geo_constraints=sprintf(" and `Order Ship To Country Code`='%s'", $where_data['delivery_geo_constraints']);
	}

	if ($where_data['product_ordered_or']=='')
		$where_data['product_ordered_or']='∀';

	if ($where_data['product_ordered_or']!='') {
		if ($where_data['product_ordered_or']!='∀') {
			$use_otf=true;
			$where_product_ordered1=true;
			//list($where_product_ordered1,$use_product)=extract_product_groups($where_data['product_ordered_or'],$where_data['store_key']);
		} else
			$where_product_ordered1='true';
	} else {
		$where_product_ordered1='false';
	}
	//print $where_product_ordered1;


	$date_interval_order_created=prepare_mysql_dates($where_data['order_created_from'], $where_data['order_created_to'], '`Order Date`', 'only_dates');

	/*
        $date_interval_when_customer_created=prepare_mysql_dates($where_data['customer_created_from'],$where_data['customer_created_to'],'`Customer First Contacted Date`','only_dates');
        if ($date_interval_when_ordered['mysql']) {
            $use_otf=true;
        }

    	$date_interval_when_ordered=prepare_mysql_dates($where_data['product_ordered_or_from'],$where_data['product_ordered_or_to'],'`Order Date`','only_dates');
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

    */


	//  $where='where (  '.$where_product_ordered1.$date_interval_when_customer_created['mysql'].") $where_billing_geo_constraints where_delivery_geo_constraints";

	$where='where (  '.$where_product_ordered1.$date_interval_order_created['mysql'].") $where_billing_geo_constraints $where_delivery_geo_constraints";
	//print $where;exit;
	/*
        foreach($where_data['dont_have'] as $dont_have) {
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
        foreach($where_data['have'] as $dont_have) {
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
    */
	/*
        $allow_where='';
       foreach($where_data['allow'] as $allow) {
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
        if($allow_where!=''){
        $where.="and ($allow_where)";
        }

        $dont_allow_where='';
       foreach($where_data['dont_allow'] as $dont_allow) {
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
        if($dont_allow_where!=''){
        $where.="and ($dont_allow_where)";
        }


    	$customers_which_where='';
       foreach($where_data['customers_which'] as $customers_which) {
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
        if($customers_which_where!=''){
        $where.="and ($customers_which_where)";
        }

    	$invoice_option_where='';
       foreach($where_data['invoice_option'] as $invoice_option) {
            switch ($invoice_option) {
            case 'less':
                $invoice_option_where.=sprintf(" and `Customer Has More Invoices Than`<'%d' ",$where_data['number_of_invoices_lower']);
                break;
            case 'equal':
                $invoice_option_where.=sprintf(" and `Customer Has More Invoices Than`='%d'  ",$where_data['number_of_invoices_lower']);
                break;
            case 'more':
                $invoice_option_where.=sprintf(" and  `Customer Has More Invoices Than`>'%d'  ",$where_data['number_of_invoices_lower']);
                break;

    		case 'between':
    			$invoice_option_where.=sprintf(" and  `Customer Has More Invoices Than`>'%d'  and `Customer Has More Invoices Than`<'%d'", $where_data['number_of_invoices_lower'], $where_data['number_of_invoices_upper']);
    			break;
    		}
        }
        $invoice_option_where=preg_replace('/^\s*and/','',$invoice_option_where);

        if($invoice_option_where!=''){
        $where.="and ($invoice_option_where)";
        }

    	$sales_option_where='';
       foreach($where_data['sales_option'] as $sales_option) {
            switch ($sales_option) {
            case 'sales_less':
                $sales_option_where.=sprintf(" and `Invoice Transaction Gross Amount`<'%s' ",$where_data['sales_lower']);
                break;
            case 'sales_equal':
                $sales_option_where.=sprintf(" and `Invoice Transaction Gross Amount`='%s'  ",$where_data['sales_lower']);
                break;
            case 'sales_more':
                $sales_option_where.=sprintf(" and  `Invoice Transaction Gross Amount`>'%s'  ",$where_data['sales_lower']);
                break;

    		case 'sales_between':
    			$sales_option_where.=sprintf(" and  `Invoice Transaction Gross Amount`>'%s'  and `Invoice Transaction Gross Amount`<'%s'", $where_data['sales_lower'], $where_data['sales_upper']);
    			break;
    		}
        }
        $sales_option_where=preg_replace('/^\s*and/','',$sales_option_where);

        if($sales_option_where!=''){
        $where.="and ($sales_option_where)";
        }

    */
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
	//print $table;
	//print '|';
	//print $where; exit;

	//exit;
	return array($where, $table);


}


?>
