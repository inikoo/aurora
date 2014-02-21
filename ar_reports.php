<?php
require_once 'common.php';
require_once 'ar_common.php';

if (!isset($output_type))
	$output_type='ajax';

if (!isset($_REQUEST['tipo'])) {
	if ($output_type=='ajax') {
		$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
		echo json_encode($response);
	}
	return;
}


$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('packed_dns'):
	list_packed_dns();
	break;
case('picked_dns'):
	list_picked_dns();
	break;
case('inventory_assets_sales_history'):
	list_inventory_assets_sales_history();
	break;
case('assets_sales_history'):
	list_assets_sales_history();
	break;


case('sales_per_store'):
	list_sales_per_store();
	break;
case('sales_per_invoice_category'):
	list_sales_per_invoice_category();
	break;
case('get_tax_categories_elements_chooser'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date'),
			'regions'=>array('type'=>'string'),
			'tax_category'=>array('type'=>'string')
			
		));

	get_tax_categories_elements_chooser($data);
	break;

case('out_of_stock_lost_revenue_data'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date')
		));

	out_of_stock_lost_revenue_data($data);
	break;

case ('sales_components'):
	list_sales_components_per_store();
	break;
case ('pending_orders'):
	list_pending_orders_per_store();
	break;
case('get_tax_categories_numbers'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date'),
			'country'=>array('type'=>'country')
		));

	get_tax_categories_numbers($data);
	break;

case('out_of_stock_customer_data'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date')
		));

	out_of_stock_customer_data($data);
	break;
case('out_of_stock_order_data'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date')
		));

	out_of_stock_order_data($data);
	break;

case('out_of_stock_data'):
	$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date')
		));

	out_of_stock_data($data);
	break;
case('intrastat'):

	list_intrastat();
	exit;
case('tax_overview'):


	switch ($corporate_country_2alpha_code) {
	case'GB':
	case'ES':
		tax_overview_europe($corporate_country_2alpha_code);
		break;
	default:
		tax_overview($corporate_country_2alpha_code);
		break;
	}
	break;
case('store_sales'):
	list_store_sales();
	break;

case('country_sales'):
	list_country_sales();
	break;
case('wregion_sales'):
	list_wregion_sales();
	break;
case('continent_sales'):
	list_continent_sales();
	break;
case('first_order_share_histogram'):

	$data=prepare_values($_REQUEST,array(
			'department_key'=>array('type'=>'key'),
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date')
		));

	first_order_share_histogram($data);
	break;

	break;
case('transactions_parts_marked_as_out_of_stock'):
	list_transactions_parts_marked_as_out_of_stock();
	break;
case('parts_marked_as_out_of_stock'):
	list_parts_marked_as_out_of_stock();
	break;
case('first_order_products'):
	list_first_order_products();
	break;
case('invoices_with_no_tax'):
	if (!$user->can_view('orders'))
		exit();

	list_invoices_with_no_tax();

	break;
case('customers_with_no_tax'):
	if (!$user->can_view('orders'))
		exit("E");

	switch ($corporate_country_2alpha_code) {
	case'GB':
	case'ES':
		list_customers_by_tax_europe($corporate_country_2alpha_code);
		break;
	default:
		list_customers_by_tax($corporate_country_2alpha_code);
		break;
	}
	break;
case('pickers_report'):
	pickers_report();
	break;

case('packers_report'):
	packers_report();
	break;
case('top_customers'):
	$results=list_top_customers();
	break;
case('products'):
	$results=list_products();
	break;
case('ES_1'):
	es_1();
	break;
case('customers_affected_by_out_of_stock'):
	list_customers_affected_by_out_of_stock();
	break;
case('orders_affected_by_out_of_stock'):
	list_orders_affected_by_out_of_stock();
	break;
default:
	$response=array('state'=>404,'resp'=>'Operation not found');
	echo json_encode($response);
}

function first_order_share_histogram($data) {
	$histogram=array('share_80'=>0,'share_60'=>0,'share_40'=>0,'share_20'=>0,'share_00'=>0);

	$sql=sprintf("select OD.`Order Key`,(`Order Items Gross Amount`) as total  from `Order Dimension` OD where `Order Customer Order Number`=1  and Date(OD.`Order Date`)>=%s and Date(OD.`Order Date`)<=%s  ",
		prepare_mysql($data['from']),
		prepare_mysql($data['to'])
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$sql=sprintf("select ifNULL(sum(`Order Transaction Gross Amount`),0) as in_department  from `Order Transaction Fact` OTF left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`) where OTF.`Order Key`=%d and `Product Main Department Key`=%d",
			$row['Order Key'],
			$data['department_key']
		);
		$res2=mysql_query($sql);

		$in_department=0;
		if ($row2=mysql_fetch_assoc($res2)) {
			$in_department=$row2['in_department'];
		}




		if ($row['total']==0)
			continue;
		$share=$in_department/$row['total'];
		if ($share<=.2) {
			$histogram['share_00']++;
		}
		elseif ($share<=.4) {
			$histogram['share_20']++;
		}
		elseif ($share<=.6) {
			$histogram['share_40']++;
		}
		elseif ($share<=.8) {
			$histogram['share_60']++;
		}
		else {
			$histogram['share_80']++;
		}
	}


	$response=array('state'=>200,'histogram'=>$histogram);
	echo json_encode($response);

}



function list_first_order_products() {


	global $user;

	$conf=$_SESSION['state']['report_first_order']['products'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['report_first_order']['products']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['report_first_order']['products']['order']=$order;
	} else
		$order=$conf['order'];
	$order_direction='desc';
	$order_dir='desc';

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_first_order']['to']=$to;
	} else
		$to=$_SESSION['state']['report_first_order']['to'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_first_order']['from']=$from;
	} else
		$from=$_SESSION['state']['report_first_order']['from'];

	if (isset( $_REQUEST['share'])) {
		$share=$_REQUEST['share'];
		$_SESSION['state']['report_first_order']['share']=$share;
	} else
		$share=$_SESSION['state']['report_first_order']['share'];


	if (isset( $_REQUEST['department_key'])) {
		$department_key=$_REQUEST['department_key'];
		$_SESSION['state']['report_first_order']['department_key']=$department_key;
	} else
		$department_key=$_SESSION['state']['report_first_order']['department_key'];


	$filter_msg='';
	$wheref='';
	$int=prepare_mysql_dates($from,$to,'`Order Date`','only dates');

	$where=sprintf('where true  %s',$int['mysql']);


	$orders_keys=array();


	$sql=sprintf("select OD.`Order Key`,(`Order Items Gross Amount`) as total  from `Order Dimension` OD where `Order Customer Order Number`=1  and Date(OD.`Order Date`)>=%s and Date(OD.`Order Date`)<=%s  ",
		prepare_mysql($from),
		prepare_mysql($to)
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {

		$sql=sprintf("select ifNULL(sum(`Order Transaction Gross Amount`),0) as in_department  from `Order Transaction Fact` OTF left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`) where OTF.`Order Key`=%d and `Product Main Department Key`=%d ",
			$row['Order Key'],
			$department_key
		);
		$res2=mysql_query($sql);

		$in_department=0;
		if ($row2=mysql_fetch_assoc($res2)) {
			$in_department=$row2['in_department'];
		}


		if ($row['total']==0) {
			continue;
		}

		$_share=$in_department/$row['total'];

		if ($_share>$share and $_share<$share+.2) {
			$orders_keys[$row['Order Key']]=$row['Order Key'];
		}

	}

	//print_r($orders_keys);

	$sql=sprintf("select  P.`Product Code`,sum(`Invoice Transaction Gross Amount`) as amount,count( distinct `Order Key`) as orders    from `Order Transaction Fact` OTF   left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`) where `Order Key` in (%s)  group by PH.`Product ID`",
		join(',',$orders_keys)
	);

	print "$sql";

	$adata=array();


	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


	}



	$adata=array();
	$rtext='';
	$_order='';
	$_dir='';
	$filtered=0;
	$total=0;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);
	return;


}



function pickers_report() {
	$conf=$_SESSION['state']['report_pp']['pickers'];
	//  if(isset( $_REQUEST['sf']))
	//      $start_from=$_REQUEST['sf'];
	//    else
	//      $start_from=$conf['sf'];
	//    if(isset( $_REQUEST['nr']))
	//      $number_results=$_REQUEST['nr'];
	//    else
	//      $number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	//    if(isset( $_REQUEST['f_field']))
	//      $f_field=$_REQUEST['f_field'];
	//    else
	//      $f_field=$conf['f_field'];

	//   if(isset( $_REQUEST['f_value']))
	//      $f_value=$_REQUEST['f_value'];
	//    else
	//      $f_value=$conf['f_value'];
	// if(isset( $_REQUEST['where']))
	//      $where=$_REQUEST['where'];
	//    else
	//      $where=$conf['where'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state']['report_pp']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['report_pp']['to'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state']['report_pp']['pickers']['order']=$order;
	$_SESSION['state']['report_pp']['pickers']['order_dir']=$order_direction;
	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['report_pp']['from'],$_SESSION['state']['report_pp']['to']);
	} else {
		$_SESSION['state']['report_pp']['from']=$date_interval['from'];
		$_SESSION['state']['report_pp']['to']=$date_interval['to'];
	}
	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Date`');

	$start_from=0;

	$filter_msg='';
	$_order=$order;
	$_dir=$order_direction;

	if ($order=='units') {
		$order ='units';
	}
	elseif ($order=='weight') {
		$order ='weight';
	}
	elseif ($order=='orders') {
		$order ='delivery_notes';
	}
	else
		$order='`Staff Name`';




	$sql=sprintf("select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from `Inventory Transaction Fact`  where `Inventory Transaction Type`='Sale'  %s   ",$date_interval['mysql']);

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total_delivery_notes=$row['delivery_notes'];
		$total_units=$row['units'];
		$total_weight=$row['weight'];
	}








	$sql=sprintf("select `Staff Name`,`Picker Key`,sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units from `Inventory Transaction Fact` left join `Staff Dimension` S on  (`Picker Key`=S.`Staff Key`)   where `Inventory Transaction Type`='Sale' %s group by `Picker Key` order by %s %s  ",
		$date_interval['mysql'],
		addslashes($order),
		addslashes($order_direction)
	);




	//print $sql;
	$result=mysql_query($sql);
	$data=array();
	$hours=40;

	$total=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//uph=$row['units']/$hours;
		//if($row['position_id']==2){
		//  $uph=number($row['units']/$hours);
		//}else
		// $uph='';

		$total++;

		if ($row['Picker Key'])
			$alias='<a href="report_pp_employee.php?view=picked&id='.$row['Picker Key'].'">'.$row['Staff Name'].'</a>';
		else
			$alias='<a href="report_pp_employee.php?view=picked&id=">'._('Unknown').'</a>';

		$data[]=array(
			//  'tipo'=>($row['position_id']==2?_('FT'):''),
			'alias'=>$alias,
			'orders'=>number($row['delivery_notes']),
			'units'=>number($row['units'],0) ,
			'weight'=>number($row['weight'],0)." Kg",
			'p_orders'=>percentage($row['delivery_notes'],$total_delivery_notes),
			'p_units'=>percentage($row['units'],$total_units),
			'p_weight'=>percentage($row['weight'],$total_weight),


			//'errors'=>number($row['errors']),
			//'epo'=>number(100*$row['epo']+0.00001,1)."%",
			//'hours'=>$hours,
			//'uph'=>$uph
		);
	}

	$number_results=$total;
	$filtered=0;
	if ($total==0) {
		$rtext=_('No order has been prepared in this period').'.';
	}
	// elseif ($total<$number_results)
	$rtext=number($total).' '.ngettext('picker','pickers',$total);
	// else
	//  $rtext='x';
	$rtext_rpp=' ('._("Showing all").')';

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			// 'records_returned'=>$start_from+$res->numRows(),
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);

}

function packers_report() {
	$conf=$_SESSION['state']['report_pp']['packers'];
	//  if(isset( $_REQUEST['sf']))
	//      $start_from=$_REQUEST['sf'];
	//    else
	//      $start_from=$conf['sf'];
	//    if(isset( $_REQUEST['nr']))
	//      $number_results=$_REQUEST['nr'];
	//    else
	//      $number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	//    if(isset( $_REQUEST['f_field']))
	//      $f_field=$_REQUEST['f_field'];
	//    else
	//      $f_field=$conf['f_field'];

	//   if(isset( $_REQUEST['f_value']))
	//      $f_value=$_REQUEST['f_value'];
	//    else
	//      $f_value=$conf['f_value'];
	// if(isset( $_REQUEST['where']))
	//      $where=$_REQUEST['where'];
	//    else
	//      $where=$conf['where'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state']['report_pp']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['report_pp']['to'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state']['report_pp']['packers']['order']=$order;
	$_SESSION['state']['report_pp']['packers']['order_dir']=$order_direction;
	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['report_pp']['from'],$_SESSION['state']['report_pp']['to']);
	} else {
		$_SESSION['state']['report_pp']['from']=$date_interval['from'];
		$_SESSION['state']['report_pp']['to']=$date_interval['to'];
	}

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Date`');

	$start_from=0;

	$filter_msg='';
	$_order=$order;
	$_dir=$order_direction;


	if ($order=='units') {
		$order ='units';
	}
	elseif ($order=='weight') {
		$order ='weight';
	}
	elseif ($order=='orders') {
		$order ='delivery_notes';
	}
	else
		$order='`Staff Name`';

	$sql=sprintf("select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from `Inventory Transaction Fact`  where `Inventory Transaction Type`='Sale'  %s   ",
		$date_interval['mysql']);

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total_delivery_notes=$row['delivery_notes'];
		$total_units=$row['units'];
		$total_weight=$row['weight'];
	}
	//print $sql;
	$sql=sprintf("select `Staff Name`,`Packer Key`,sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units from `Inventory Transaction Fact` left join `Staff Dimension` S on  (`Packer Key`=S.`Staff Key`)   where `Inventory Transaction Type`='Sale' %s group by `Packer Key` order by %s %s  ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));


	$result=mysql_query($sql);
	$data=array();
	$hours=40;

	$total=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//uph=$row['units']/$hours;
		//if($row['position_id']==2){
		//  $uph=number($row['units']/$hours);
		//}else
		// $uph='';

		$total++;

		if ($row['Packer Key'])
			$alias='<a href="report_pp_employee.php?view=packed&id='.$row['Packer Key'].'">'.$row['Staff Name'].'</a>';
		else
			$alias='<a href="report_pp_employee.php?view=packed&id=" style="color:#777;font-style:italic" >'._('Unknown').'</a>';


		$data[]=array(
			//  'tipo'=>($row['position_id']==2?_('FT'):''),
			'alias'=>$alias,
			'orders'=>number($row['delivery_notes']),
			'units'=>number($row['units'],0) ,
			'weight'=>number($row['weight'],0)." "._('Kg'),
			'p_orders'=>percentage($row['delivery_notes'],$total_delivery_notes),
			'p_units'=>percentage($row['units'],$total_units),
			'p_weight'=>percentage($row['weight'],$total_weight),
			//'errors'=>number($row['errors']),
			//'epo'=>number(100*$row['epo']+0.00001,1)."%",
			//'hours'=>$hours,
			//'uph'=>$uph
		);
	}

	$number_results=$total;
	$filtered=0;
	if ($total==0) {
		$rtext=_('No order has been placed yet').'.';
	}
	//elseif ($total<$number_results)
	// $rtext=$total.' '.ngettext('record returned','records returned',$total);
	//else
	// $rtext='';
	//$rtext_rpp='';


	// elseif ($total<$number_results)
	$rtext=number($total).' '.ngettext('packer','packers',$total);
	// else
	//  $rtext='x';
	$rtext_rpp=' ('._("Showing all").')';

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'rtext_rpp'=>$rtext_rpp,
			'rtext'=>$rtext,
			'records_offset'=>$start_from,
			// 'records_returned'=>$start_from+$res->numRows(),
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);

}


function es_1() {
	global $myconf,$corporate_currency;

	$conf=$_SESSION['state']['customers']['customers'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['year']))
		$year=$_REQUEST['year'];
	else
		$year=date('Y',strtotime('today -1 year'));

	if (isset( $_REQUEST['umbral']))
		$umbral=$_REQUEST['umbral'];
	else
		$umbral=3000;



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['store_id'])    ) {
		$store=$_REQUEST['store_id'];
		$_SESSION['state']['customers']['store']=$store;
	} else
		$store=$_SESSION['state']['customers']['store'];


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_SESSION['state']['customers']['customers']['order']=$order;
	$_SESSION['state']['customers']['customers']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['customers']['nr']=$number_results;
	$_SESSION['state']['customers']['customers']['sf']=$start_from;
	$_SESSION['state']['customers']['customers']['where']=$where;
	$_SESSION['state']['customers']['customers']['f_field']=$f_field;
	$_SESSION['state']['customers']['customers']['f_value']=$f_value;
	$filter_msg='';
	$wheref='';

	$where=' where true ';

	if (is_numeric($store)) {
		$where.=sprintf(' and `Customer Store Key`=%d ',$store);
	}

	$where.=sprintf(' and `Customer Main Country Code`="ESP"   and Year(`Invoice Date`)=%d',$year );


	$rtext='';
	$filtered=0;
	$_order='';
	$_dir='';
	$total=0;

	$sql="select  GROUP_CONCAT(`Invoice Key`) as invoice_keys,sum(`Invoice Total Tax Adjust Amount`*`Invoice Currency Exchange`) as adjust_tax,`Customer Main Location`,`Customer Key`,`Customer Name`,`Customer Main XHTML Email`,count(DISTINCT `Invoice Key`) as invoices,sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total, sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net from  `Invoice Dimension` I left join  `Customer Dimension` C  on (I.`Invoice Customer Key`=C.`Customer Key`)  $where $wheref  group by `Customer Key` order by total desc";
	//   print $sql;
	$adata=array();


	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		if ($data['total']<$umbral)
			break;
		$total++;

		$tax1=0;
		$tax2=0;

		$sql2=sprintf("select `Tax Code`,sum(`Tax Amount`*`Invoice Currency Exchange`) as amount from `Invoice Tax Bridge` T left join `Invoice Dimension` I on (T.`Invoice Key`=I.`Invoice Key`) where T.`Invoice Key` in (%s) group by `Tax Code`  ", $data['invoice_keys']);
		$res2=mysql_query($sql2);

		$tax1=0;
		$tax2=0;

		while ($row2=mysql_fetch_array($res2)) {
			if ($row2['Tax Code']=='S1') {
				$tax1+=$row2['amount'];
			}
			elseif ($row2['Tax Code']=='S2') {
				$tax2+=$row2['amount'];
			}
			elseif ($row2['Tax Code']=='S3') {
				$tax1+=0.8*$row2['amount'];
				$tax2+=0.2*$row2['amount'];
			}elseif ($row2['Tax Code']=='S4') {
				$tax1+=$row2['amount'];
			}elseif ($row2['Tax Code']=='S5') {
				$tax1+=0.81818181*$row2['amount'];
				$tax2+=0.18181818*$row2['amount'];
			}elseif ($row2['Tax Code']=='UNK') {
				$tax1+=$row2['amount'];
			}


		}

		if ($tax2>0 and $tax1==0) {
			$tax2+=$data['adjust_tax'];

		} else {

			$tax1+=$data['adjust_tax'];
		}

		$id="<a href='customer.php?id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
		$name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';


		$adata[]=array(
			'id'=>$id,
			'name'=>$name,
			'total'=>money($data['total'],$corporate_currency),
			'net'=>money($data['net'],$corporate_currency),
			'tax1'=>money($tax1,$corporate_currency),
			'tax2'=>money($tax2,$corporate_currency),
			'invoices'=>number($data['invoices']),
			'location'=>$data['Customer Main Location']


		);
	}
	mysql_free_result($result);

	$rtext=number($total).' '.ngettext('customer', 'customers', $total);
	$rtext_rpp=' ('._('Showing all').')';

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,


			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_products() {


	global $myconf,$output_type,$user;

	$conf=$_SESSION['state']['report']['products'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['report']['products']['top']=$number_results;
	} else
		$number_results=$conf['top'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['report']['products']['criteria']=$order;
	} else
		$order=$conf['criteria'];
	$order_direction='desc';
	$order_dir='desc';

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report']['products']['to']=$to;
	} else
		$to=$conf['to'];



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report']['products']['from']=$from;
	} else
		$from=$conf['from'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['store_keys'])    ) {
		$store=$_REQUEST['store_keys'];
		$_SESSION['state']['report']['products']['store_keys']=$store;
	} else
		$store=$_SESSION['state']['report']['products']['store_keys'];

	if ($store=='all') {
		$store=join(',',$user->stores);

	}


	$filter_msg='';
	$wheref='';
	$int=prepare_mysql_dates($from,$to,'`Invoice Date`','only dates');

	$where=sprintf('where true  %s',$int['mysql']);


	if (is_numeric($store)) {
		$where.=sprintf(' and `Product Store Key`=%d ',$store);
	}
	elseif ($store=='') {

		$where.=sprintf(' and false ',$store);

	}
	else {

		$where.=sprintf(' and `Product Store Key` in (%s) ',$store);

	}


	$filtered=0;
	$rtext='';
	$total=$number_results;



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='profits')
		$order='`Product 1 Year Acc Profit`';

	else
		$order='`Product 1 Year Acc Invoiced Amount`';


	$sql="select  * from `Product Dimension` P  left join `Store Dimension` S on (P.`Product Store Key`=S.`Store Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results";
	$adata=array();


	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$sales=money($data['Product 1 Year Acc Invoiced Amount'],$data['Store Currency Code']);



		$code="<a href='product.php?pid=".$data['Product ID']."'>".$data['Product Code'].'</a>';
		$family="<a href='family.php?id=".$data['Product Family Key']."'>".$data['Product Family Code'].'</a>';
		$store="<a href='store.php?id=".$data['Product Store Key']."'>".$data['Store Code'].'</a>';

		$adata[]=array(
			'position'=>'<b>'.$position++.'</b>'
			,'code'=>$code
			,'family'=>$family
			,'store'=>$store
			,'description'=>'<b>'.$code.'</b> '.$data['Product Short Description']
			,'net_sales'=>$sales
		);
	}
	mysql_free_result($result);




	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$_order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	if ($output_type=='ajax') {
		echo json_encode($response);
		return;
	} else {
		return $response;
	}

}
function list_top_customers() {


	global $myconf,$output_type,$user,$corporate_currency;

	$conf=$_SESSION['state']['report_customers'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['report_customers']['top']=$number_results;
	} else
		$number_results=$conf['top'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['report_customers']['criteria']=$order;
	} else
		$order=$conf['criteria'];
	$order_direction='desc';
	$order_dir='desc';

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_customers']['to']=$to;
	} else
		$to=$conf['to'];



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_customers']['from']=$from;
	} else
		$from=$conf['from'];




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['store_keys'])    ) {
		$store=$_REQUEST['store_keys'];
		$_SESSION['state']['report_customers']['store_keys']=$store;
	} else
		$store=$_SESSION['state']['report_customers']['store_keys'];

	if ($store=='all') {
		$store=join(',',$user->stores);

	}


	$filter_msg='';
	$wheref='';
	$int=prepare_mysql_dates($from,$to,'`Invoice Date`','only dates');

	$where=sprintf('where true  %s',$int['mysql']);


	if (is_numeric($store)) {
		$where.=sprintf(' and `Customer Store Key`=%d ',$store);
	}
	elseif ($store=='') {

		$where.=sprintf(' and false ',$store);

	}
	else {

		$where.=sprintf(' and `Customer Store Key` in (%s) ',$store);

	}


	$filtered=0;

	$total=$number_results;



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='invoices')
		$order='`Invoices`';

	else
		$order='`Balance`';


	$sql="select  `Store Code`,`Customer Type by Activity`,`Customer Last Order Date`,`Customer Main XHTML Telephone`,`Customer Key`,`Customer Name`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main Town`,`Customer Main Country First Division`,`Customer Main Delivery Address Postal Code`,count(distinct `Invoice Key`) as Invoices , sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as Balance  from `Customer Dimension` C  left join `Store Dimension` SD on (C.`Customer Store Key`=SD.`Store Key`)  left join `Invoice Dimension` I on (`Invoice Customer Key`=`Customer Key`)  $where $wheref  group by `Customer Key` order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	// print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$id="<a href='customer.php?id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
		$name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';

		$adata[]=array(
			'position'=>'<b>'.$position++.'</b>',
			'id'=>$id,
			'name'=>$name,
			'store'=>$data['Store Code'],
			'location'=>$data['Customer Main Location'],
			//  'orders'=>number($data['Customer Orders']),
			'invoices'=>$data['Invoices'],
			'email'=>$data['Customer Main XHTML Email'],
			'telephone'=>$data['Customer Main XHTML Telephone'],
			'last_order'=>strftime("%e %b %Y", strtotime($data['Customer Last Order Date'])),
			// 'total_payments'=>money($data['Customer Net Payments']),
			'net_balance'=>money($data['Balance'],$corporate_currency),
			//'total_refunds'=>money($data['Customer Net Refunds']),
			//'total_profit'=>money($data['Customer Profit']),
			//'balance'=>money($data['Customer Outstanding Net Balance']),


			//'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
			//'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
			//'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
			//'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
			//'contact_name'=>$data['Customer Main Contact Name'],
			//'address'=>$data['Customer Main Location'],
			//'town'=>$data['Customer Main Town'],
			//'postcode'=>$data['Customer Main Postal Code'],
			//'region'=>$data['Customer Main Country First Division'],
			//'country'=>$data['Customer Main Country'],
			//     'ship_address'=>$data['customer main ship to header'],
			//'ship_town'=>$data['Customer Main Delivery Address Town'],
			//'ship_postcode'>$data['Customer Main Delivery Address Postal Code'],
			//'ship_region'=>$data['Customer Main Delivery Address Country Region'],
			//'ship_country'=>$data['Customer Main Delivery Address Country'],
			'activity'=>$data['Customer Type by Activity']

		);
	}
	mysql_free_result($result);

	$_records=$position-1;
	$rtext=_('Top').' '.number($_records).' '.ngettext('customer','customers', $_records).' ';
	if ($order=='invoices')
		$rtext.=_('by number of invoices');

	else
		$rtext.=_('by balance');

	$rtext.=' <span  onClick="show_dialog_options()"><img src="art/down_arrow.png"  style="margin-left:2px;cursor:pointer;vertical-align:1px" ></span>';

	$rtext_rpp='';

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	if ($output_type=='ajax') {
		echo json_encode($response);
		return;
	} else {
		return $response;
	}

}

function list_parts_marked_as_out_of_stock() {


	global $myconf,$output_type,$user,$corporate_currency;

	if (isset($_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		exit('no parent');

	if (isset($_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit('no parent_key');



	$conf=$_SESSION['state']['report_part_out_of_stock']['parts'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['report_part_out_of_stock']['parts']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['report_part_out_of_stock']['parts']['order']=$order;
	} else
		$order=$conf['order'];
	if (isset( $_REQUEST['od'])) {
		$order_dir=$_REQUEST['od'];
		$_SESSION['state']['report_part_out_of_stock']['parts']['order_dir']=$order_dir;

	}else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_part_out_of_stock']['to']=$to;
	} else
		$to=$_SESSION['state']['report_part_out_of_stock']['to'];



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_part_out_of_stock']['from']=$from;
	} else
		$from= $_SESSION['state']['report_part_out_of_stock']['from'];




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$_SESSION['state']['report_part_out_of_stock']['parts']['f_field']=$f_field;
	$_SESSION['state']['report_part_out_of_stock']['parts']['f_value']=$f_value;


	$filter_msg='';
	$wheref='';
	// $int=prepare_mysql_dates($from,$to,'`Invoice Date`','only dates');
	$int=prepare_mysql_dates($from,$to,'`Date`','only dates');
	//print"$from --> $to ";
	// print_r($int);

	$where='where ITF.`Out of Stock Tag`="Yes" ';

	if ($int['mysql']!='') {
		$where.=sprintf('  %s ',$int['mysql']);

	}


	if ($parent=='warehouses') {

		if (count($user->warehouses)==0) {
			$where.=sprintf(' and false ',$store);

		}else {

			$where.=sprintf(' and ITF.`Warehouse Key` in (%s) ',join(',',$user->warehouses));
		}
	}elseif ($parent=='warehouse') {

		$where.=sprintf(' and ITF.`Warehouse Key`=%d ',$parent+key);

	}else {
		exit();
	}





	$wheref='';
	if ($f_field=='sku' and $f_value!='')
		$wheref.=" and  ITF.`Part SKU` like '".addslashes($f_value)."%'";

	elseif ($f_field=='reference' and $f_value!='')
		$wheref.=" and  `Part Reference` like '".addslashes($f_value)."%'";

	$sql="select count(DISTINCT ITF.`Part SKU`) as total   from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)   $where $wheref ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(DISTINCT ITF.`Part SKU`) as total_without_filters  from `Inventory Transaction Fact` ITF   $where ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with SKU like")." <b>".$f_value."*</b> ";
			break;
		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part used in")." <b>".$f_value."*</b> ";
			break;
		case('reference'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with reference like")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with SKU like')." <b>".$f_value."*</b>";
			break;
		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts used in')." <b>".$f_value."*</b>";
			break;
		case('reference'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with reference like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';






	$_order=$order;
	$_dir=$order_direction;



	if ($order=='reference')
		$order='`Part Reference`';
	elseif ($order=='reporter')
		$order='`Staff Alias`';
	elseif ($order=='lost_revenue')
		$order='lost_revenue';
	elseif ($order=='orders')
		$order='`Orders`';
	elseif ($order=='qty')
		$order='qty';
	elseif ($order=='customers')
		$order='`Customers`';
	elseif ($order=='sku')
		$order='`Part SKU`';
	else
		$order='`Date Picked`';

	$sql="select sum(`Out of Stock`) as qty,sum(`Invoice Currency Exchange Rate`*`Order Out of Stock Lost Amount`) as lost_revenue, `Part Reference`,count(DISTINCT `Customer Key`) as Customers,count(DISTINCT `Order Key`) as Orders,ITF.`Part SKU`,`Part XHTML Currently Used In`,MAX(`Date Picked`) as `Date Picked` from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) left join `Staff Dimension` SD on (SD.`Staff Key`=ITF.`Picker Key`)  $where $wheref  group by ITF.`Part SKU` order by $order $order_direction limit $start_from,$number_results";


	$adata=array();

	//print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$adata[]=array(

			'sku'=>sprintf("<a href='part.php?sku=%d'>SKU%05d</a>",$data['Part SKU'],$data['Part SKU']),
			'reference'=>$data['Part Reference'],
			'used_in'=>$data['Part XHTML Currently Used In'],
			'date'=>strftime("%a %e %b %y %H:%M %Z", strtotime($data['Date Picked']." +00:00")),
			'orders'=>number($data['Orders']),
			'customers'=>number($data['Customers']),
			'qty'=>number($data['qty']),

			'lost_revenue'=>money($data['lost_revenue'],$corporate_currency)

		);
	}
	mysql_free_result($result);




	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);


	// if($output_type=='ajax'){
	echo json_encode($response);
	//  return;
	// }else{
	//   return $response;
	// }

}

function list_transactions_parts_marked_as_out_of_stock() {


	global $myconf,$output_type,$user,$corporate_currency;


	if (isset($_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		exit('no parent');

	if (isset($_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit('no parent_key');




	$conf=$_SESSION['state']['report_part_out_of_stock']['transactions'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['report_part_out_of_stock']['transactions']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['report_part_out_of_stock']['transactions']['order']=$order;
	} else
		$order=$conf['order'];

	if (isset( $_REQUEST['od'])) {
		$order_dir=$_REQUEST['od'];
		$_SESSION['state']['report_part_out_of_stock']['transactions']['order_dir']=$order_dir;

	}else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_part_out_of_stock']['to']=$to;
	} else
		$to=$_SESSION['state']['report_part_out_of_stock']['to'];



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_part_out_of_stock']['from']=$from;
	} else
		$from= $_SESSION['state']['report_part_out_of_stock']['from'];




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$_SESSION['state']['report_part_out_of_stock']['transactions']['f_field']=$f_field;
	$_SESSION['state']['report_part_out_of_stock']['transactions']['f_value']=$f_value;


	$filter_msg='';
	$wheref='';
	// $int=prepare_mysql_dates($from,$to,'`Invoice Date`','only dates');
	$int=prepare_mysql_dates($from,$to,'`Date`','only dates');





	//print"$from --> $to ";
	// print_r($int);

	$where='where ITF.`Out of Stock Tag`="Yes" ';

	if ($int['mysql']!='') {
		$where.=sprintf('  %s ',$int['mysql']);

	}


	if ($parent=='warehouses') {

		if (count($user->warehouses)==0) {
			$where.=sprintf(' and false ',$store);

		}else {

			$where.=sprintf(' and ITF.`Warehouse Key` in (%s) ',join(',',$user->warehouses));
		}
	}elseif ($parent=='warehouse') {

		$where.=sprintf(' and ITF.`Warehouse Key`=%d ',$parent+key);

	}else {
		exit();
	}





	$wheref='';
	if ($f_field=='sku' and $f_value!='')
		$wheref.=" and  ITF.`Part SKU` like '".addslashes($f_value)."%'";
	elseif ($f_field=='product' and $f_value!='')
		$wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='picker' and $f_value!='')
		$wheref.=" and  `Staff Alias` like '".addslashes($f_value)."%'";
	elseif ($f_field=='order' and $f_value!='')
		$wheref.=" and  `Order Public ID` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total   from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)   left join `Staff Dimension` SD on (SD.`Staff Key`=ITF.`Picker Key`)   $where $wheref";
	// print "$sql";
	// exit;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters  from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)    $where ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with sku like")." <b>".$f_value."*</b> ";
			break;
		case('product'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like")." <b>".$f_value."*</b> ";
			break;
		case('picker'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any picker like")." <b>".$f_value."*</b> ";
			break;
		case('order'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order like")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with sku like')." <b>".$f_value."*</b>";
			break;
		case('product'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('picker'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('pickers like')." <b>".$f_value."*</b>";
			break;
		case('order'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('orders like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';






	$_order=$order;
	$_dir=$order_direction;

	if ($order=='sku')
		$order='`Part SKU`';
	elseif ($order=='date')
		$order='`Date Picked`';
	elseif ($order=='picker')
		$order='`Staff Alias`';
	elseif ($order=='lost_revenue')
		$order='lost_revenue';
	elseif ($order=='order')
		$order='`Order Public ID`';
	elseif ($order=='qty')
		$order='`Out of Stock`';
	elseif ($order=='product')
		$order='`Product Code`';
	else
		$order='`Date Picked`';


	$sql="select `Order Out of Stock Lost Amount`*`Invoice Currency Exchange Rate` as lost_revenue,`Order Key`,`Order Public ID`,`Out of Stock`,`Product Code`,`Product ID`,`Note`,SD.`Staff Alias`,ITF.`Part SKU`,`Part XHTML Currently Used In`,`Date Picked`,ITF.`Picker Key` from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) left join `Staff Dimension` SD on (SD.`Staff Key`=ITF.`Picker Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	//print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		if ($data['Picker Key'])
			$reporter=sprintf("<a href='report_out_of_stock_staff.php?id=%d'>%s</a>",$data['Picker Key'],$data['Staff Alias']);
		else
			$reporter=sprintf("<a href='report_out_of_stock_staff.php?id=0'>%s</a>",_('Unknown'));

		$adata[]=array(

			'sku'=>sprintf("<a href='part.php?sku=%d'>SKU%05d</a>",$data['Part SKU'],$data['Part SKU']),
			'product'=>sprintf("<a href='product.php?pid=%d'>%s</a>",$data['Product ID'],$data['Product Code']),
			'order'=>sprintf("<a href='product.php?pid=%d'>%s</a>",$data['Order Key'],$data['Order Public ID']),

			'date'=>strftime("%a %e %b %y %H:%M %Z", strtotime($data['Date Picked']." +00:00")),
			'picker'=>$reporter,
			'qty'=>number($data['Out of Stock']),
			'note'=>$data['Note'],
			'lost_revenue'=>money($data['lost_revenue'],$corporate_currency)

		);
	}
	mysql_free_result($result);




	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);


	// if($output_type=='ajax'){
	echo json_encode($response);
	//  return;
	// }else{
	//   return $response;
	// }

}


function list_customers_affected_by_out_of_stock() {


	global $myconf,$output_type,$user,$corporate_currency;

	$conf=$_SESSION['state']['report_part_out_of_stock']['customers'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['report_part_out_of_stock']['customers']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['report_part_out_of_stock']['customers']['order']=$order;
	} else
		$order=$conf['order'];


	if (isset( $_REQUEST['od'])) {
		$order_dir=$_REQUEST['od'];
		$_SESSION['state']['report_part_out_of_stock']['customers']['order_dir']=$order_dir;

	}else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_part_out_of_stock']['to']=$to;
	} else
		$to=$_SESSION['state']['report_part_out_of_stock']['to'];



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_part_out_of_stock']['from']=$from;
	} else
		$from= $_SESSION['state']['report_part_out_of_stock']['from'];




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=1;

	$_SESSION['state']['report_part_out_of_stock']['customers']['f_field']=$f_field;
	$_SESSION['state']['report_part_out_of_stock']['customers']['f_value']=$f_value;


	$filter_msg='';
	$wheref='';

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Dispatched Date`');

	$where=sprintf("where `Order Current Dispatch State`='Dispatched' and `Order with Out of Stock`='Yes'   %s ",$date_interval['mysql']);





	$stores=$user->stores;

	if (count($stores)>0) {
		$where.=sprintf(' and `Order Store Key` in (%s) ',join(',',$stores));
	}else {
		$where.=' and flase';
	}







	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref=sprintf('  and  `Customer Name`  REGEXP "[[:<:]]%s" ',addslashes($f_value));



	$sql="select count(DISTINCT `Order Customer Key`) as total   from  `Order Dimension` O  left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`)  $where $wheref ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(DISTINCT `Order Customer Key`) as total_without_filters   from `Order Dimension` O  left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`)  $where  ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with name like ")." <b>".$f_value."*</b> ";
			break;

		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('customers with name like')." <b>".$f_value."*</b>";
			break;

		}
	}
	else
		$filter_msg='';






	$_order=$order;
	$_dir=$order_direction;


	if ($order=='customer')
		$order='`Customer Name`';
	elseif ($order=='orders')
		$order='`Orders`';
	elseif ($order=='lost_revenue')
		$order='lost_revenue';
	elseif ($order=='lost_revenue_percentage')
		$order='lost_revenue_percentage';
	elseif ($order=='orders_percentage')
		$order='orders_percentage';

	else
		$order='`Order Dispatched Date`';


	$sql="select
	(select count(*) from `Order Dimension` where `Order Customer Key`=O.`Order Customer Key` and `Order Current Dispatch State`='Dispatched'  ".$date_interval['mysql'].")/count(DISTINCT `Order Key`) as orders_percentage,
	(select sum(`Order Items Net Amount`) from `Order Dimension` where `Order Customer Key`=O.`Order Customer Key` and `Order Current Dispatch State`='Dispatched'  ".$date_interval['mysql'].")/sum(`Order Out of Stock Net Amount`) as lost_revenue_percentage,
	sum(`Order Currency Exchange`*`Order Out of Stock Net Amount`) as lost_revenue, `Order Customer Key`,`Customer Name`,count(DISTINCT `Order Key`) as Orders,MAX(`Order Dispatched Date`) as `Order Dispatched Date`  from  `Order Dimension` O  left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`)  $where $wheref  group by `Order Customer Key` order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	// print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$adata[]=array(

			'customer'=>sprintf("<a href='customer.php?id=%d'>%s</a>",$data['Order Customer Key'],$data['Customer Name']),
			'date'=>strftime("%a %e %b %y %H:%M %Z", strtotime($data['Order Dispatched Date']." +00:00")),
			'orders'=>number($data['Orders']),
			'lost_revenue'=>money($data['lost_revenue'],$corporate_currency),
			'orders_percentage'=>percentage(1,$data['orders_percentage']),
			'lost_revenue_percentage'=>percentage(1,$data['lost_revenue_percentage']),
			//'products'=>$data['products']
		);
	}
	mysql_free_result($result);




	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);


	// if($output_type=='ajax'){
	echo json_encode($response);
	//  return;
	// }else{
	//   return $response;
	// }

}


function list_orders_affected_by_out_of_stock() {


	global $myconf,$output_type,$user,$corporate_currency;

	$conf=$_SESSION['state']['report_part_out_of_stock']['orders'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['report_part_out_of_stock']['orders']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['report_part_out_of_stock']['orders']['order']=$order;
	} else
		$order=$conf['order'];


	if (isset( $_REQUEST['od'])) {
		$order_dir=$_REQUEST['od'];
		$_SESSION['state']['report_part_out_of_stock']['orders']['order_dir']=$order_dir;

	}else
		$order_dir=$conf['order_dir'];

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_part_out_of_stock']['to']=$to;
	} else
		$to=$_SESSION['state']['report_part_out_of_stock']['to'];



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_part_out_of_stock']['from']=$from;
	} else
		$from= $_SESSION['state']['report_part_out_of_stock']['from'];




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=1;

	$_SESSION['state']['report_part_out_of_stock']['orders']['f_field']=$f_field;
	$_SESSION['state']['report_part_out_of_stock']['orders']['f_value']=$f_value;


	$filter_msg='';
	$wheref='';

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Dispatched Date`');

	$where=sprintf("where `Order Current Dispatch State`='Dispatched' and `Order with Out of Stock`='Yes'   %s ",$date_interval['mysql']);





	$stores=$user->stores;

	if (count($stores)>0) {
		$where.=sprintf(' and `Order Store Key` in (%s) ',join(',',$stores));
	}else {
		$where.=' and flase';
	}







	$wheref='';
	if (($f_field=='customer_name')  and $f_value!='') {
		$wheref=sprintf('  and  `Order Customer Name`  REGEXP "[[:<:]]%s" ',addslashes($f_value));
	}elseif ($f_field=='public_id'  and $f_value!='')
		$wheref.=" and  `Order Public ID`  like '".addslashes($f_value)."%'";



	$sql="select count(DISTINCT `Order Key`) as total   from  `Order Dimension` O    $where $wheref ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(DISTINCT `Order Key`) as total_without_filters   from `Order Dimension` O  $where  ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with customer like")." <b>$f_value</b> ";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No order with number like")." <b>$f_value</b> ";
			break;


		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('customer_name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with customer like')." <b>*".$f_value."*</b>";
			break;
		case('public_id'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('order','orders',$total)." "._('with number like')." <b>".$f_value."*</b>";
			break;

		}
	}
	else
		$filter_msg='';






	$_order=$order;
	$_dir=$order_direction;


	if ($order=='customer')
		$order='`Order Customer Name`';
	elseif ($order=='public_id')
		$order='`Order Public ID`';
	elseif ($order=='lost_revenue')
		$order='lost_revenue';
	elseif ($order=='lost_revenue_percentage')
		$order='lost_revenue_percentage';

	else
		$order='`Order Dispatched Date`';


	$sql="select `Order Public ID`,`Order Key`,IFNULL(`Order Items Net Amount`/`Order Out of Stock Net Amount`,0) as lost_revenue_percentage ,`Order Currency Exchange`*`Order Out of Stock Net Amount` as lost_revenue, `Order Customer Key`,`Order Customer Name`, `Order Dispatched Date`  from  `Order Dimension` O    $where $wheref   order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	// print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$adata[]=array(
			'public_id'=>sprintf("<a href='order.php?id=%d'>%s</a>",$data['Order Key'],$data['Order Public ID']),
			'customer'=>sprintf("<a href='customer.php?id=%d'>%s</a>",$data['Order Customer Key'],$data['Order Customer Name']),
			'date'=>strftime("%a %e %b %y %H:%M %Z", strtotime($data['Order Dispatched Date']." +00:00")),
			'lost_revenue'=>money($data['lost_revenue'],$corporate_currency),
			'lost_revenue_percentage'=>percentage(1,$data['lost_revenue_percentage']),
		);
	}
	mysql_free_result($result);




	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);


	// if($output_type=='ajax'){
	echo json_encode($response);
	//  return;
	// }else{
	//   return $response;
	// }

}



function list_invoices_with_no_tax() {


	global $corporate_country_2alpha_code;

	$conf=$_SESSION['state']['report_sales_with_no_tax']['invoices'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr']-1;

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=stripslashes($_REQUEST['where']);
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_sales_with_no_tax']['from']=$from;

	}else {

		$from=$_SESSION['state']['report_sales_with_no_tax']['from'];

	}

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_sales_with_no_tax']['to']=$to;
	}else {

		$to=$_SESSION['state']['report_sales_with_no_tax']['to'];

	}

	if (isset( $_REQUEST['currency_type'])) {
		$currency_type=$_REQUEST['currency_type'];
		$_SESSION['state']['report_sales_with_no_tax']['currency_type']=$currency_type;
	} else {
		$currency_type=$_SESSION['state']['report_sales_with_no_tax']['currency_type'];
	}

	$country=$corporate_country_2alpha_code;
	$elements_region=$_SESSION['state']['report_sales_with_no_tax'][$country]['regions'];

	if (isset( $_REQUEST['elements_region_GBIM_invoices'])) {
		$elements_region['GBIM']=$_REQUEST['elements_region_GBIM_invoices'];
	}
	if (isset( $_REQUEST['elements_region_EU_invoices'])) {
		$elements_region['EU']=$_REQUEST['elements_region_EU_invoices'];
	}
	if (isset( $_REQUEST['elements_region_NOEU_invoices'])) {
		$elements_region['NOEU']=$_REQUEST['elements_region_NOEU_invoices'];
	}
	if (isset( $_REQUEST['elements_region_ES_invoices'])) {
		$elements_region['NOEU']=$_REQUEST['elements_region_ES_invoices'];
	}
	$_SESSION['state']['report_sales_with_no_tax'][$country]['regions']=$elements_region;




	$elements_tax_category=$_SESSION['state']['report_sales_with_no_tax'][$country]['tax_category'];

	foreach ($elements_tax_category as $key=>$value) {
		if (isset( $_REQUEST['elements_tax_category_'.$key.'_invoices'])) {
			$elements_tax_category[$key]=$_REQUEST['elements_tax_category_'.$key.'_invoices'];
		}

	}


	$_SESSION['state']['report_sales_with_no_tax'][$country]['tax_category']=$elements_tax_category;




	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	//print $where;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$stores=$_SESSION['state']['report_sales_with_no_tax']['stores'];




	//  $_SESSION['state']['report_sales_with_no_tax']['invoices']=array('f_show'=>$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_show']   ,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
	//$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_show']=
	$_SESSION['state']['report_sales_with_no_tax']['invoices']['order']=$order;
	$_SESSION['state']['report_sales_with_no_tax']['invoices']['order_dir']=$order_direction;
	$_SESSION['state']['report_sales_with_no_tax']['invoices']['nr']=$number_results;
	$_SESSION['state']['report_sales_with_no_tax']['invoices']['sf']=$start_from;
	$_SESSION['state']['report_sales_with_no_tax']['invoices']['where']=$where;
	$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_field']=$f_field;
	$_SESSION['state']['report_sales_with_no_tax']['invoices']['f_value']=$f_value;



	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['report_sales_with_no_tax']['from'],$_SESSION['state']['report_sales_with_no_tax']['to']);
	} else {
		$_SESSION['state']['report_sales_with_no_tax']['from']=$date_interval['from'];
		$_SESSION['state']['report_sales_with_no_tax']['to']=$date_interval['to'];
	}




	$where=sprintf(' where  `Invoice Store Key` in (%s) ',$stores);




	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where.=$where_interval['mysql'];

	//$where.=$date_interval['mysql'];


	// $where.=sprintf(" and   `Invoice Date`>=%s and   `Invoice Date`<=%s   "
	//  ,prepare_mysql($date_interval['from'].' 00:00:00')
	//  ,prepare_mysql($date_interval['to'].' 23:59:59')
	// );


	//$where.=


	//print "F:$from to:$to $where";
	//exit;
	$where_elements_tax_category='';

	$tax_categories=array();
	foreach ($elements_tax_category as $key=>$value) {
		if ($value) {
			$tax_categories[]=prepare_mysql($key);
		}
	}
	if (count($tax_categories)==0) {
		$where.=" and false ";
	}else {
		$where.=" and `Invoice Tax Code` in (".join($tax_categories,',').") ";

	}


	if ($country=='GB') {
		$_country='"GB","IM"';
	}else {
		$_country='"'.$country.'"';

	}



	$where_elements_region='';
	if (isset($elements_region['GBIM']) and $elements_region['GBIM']) {
		$where_elements_region.=' or `Invoice Billing Country 2 Alpha Code` in ('.$_country.')  ';
	}
	if ($elements_region['EU']) {
		$where_elements_region.=' or ( `Invoice Billing Country 2 Alpha Code` not in ('.$_country.') and `European Union`="Yes" ) ';
	}
	if ($elements_region['NOEU']) {
		$where_elements_region.=' or (`Invoice Billing Country 2 Alpha Code` not in ('.$_country.') and `European Union`="No")  ';
	}
	$where_elements_region=preg_replace('/^\s*or\s*/','',$where_elements_region);
	if ( $where_elements_region=='')
		$where_elements_region=' false ';
	$where.=" and ($where_elements_region) ";








	$wheref='';

	if ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$f_value."    ";
	else if ($f_field=='min' and is_numeric($f_value) )
			$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$f_value."    ";
		elseif ($f_field=='customer_name' and $f_value!='')
			$wheref.=" and  `Invoice Customer Name` like   '".addslashes($f_value)."%'";
		elseif ( $f_field=='public_id' and $f_value!='')
			$wheref.=" and  `Invoice Public ID` like '".addslashes($f_value)."%'";

		else if ($f_field=='maxvalue' and is_numeric($f_value) )
				$wheref.=" and  `Invoice Total Amount`<=".$f_value."    ";
			else if ($f_field=='minvalue' and is_numeric($f_value) )
					$wheref.=" and  `Invoice Total Amount`>=".$f_value."    ";






				$sql="select count(*) as total from `Invoice Dimension` left join `Customer Dimension` on (`Invoice Customer Key`=`Customer Key`)  left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)  $where $wheref ";
			// print $sql;
			$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total=$row['total'];
		}
	mysql_free_result($res);
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(*) as total from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)   left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)  $where";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($res);
	}
	$rtext=number($total_records)." ".ngettext('invoice','invoices',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all invoices");

	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('customer_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;
	case('maxvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
		break;
	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date')
		$order='`Invoice Date`';
	else if ($order=='last_date')
			$order='`Invoice Last Updated Date`';
		else if ($order=='id')
				$order='`Invoice File As`';
			else if ($order=='state')
					$order='`Invoice Current Dispatch State`,`Invoice Current Payment State`';

				else if ($order=='customer')
						$order='`Invoice Customer Name`';

					else if ($order=='net')
							$order='`Invoice Total Net Amount`';
						else if ($order=='total_amount') {
								if ($currency_type=='hm_revenue_and_customs')
									$order='`Invoice Total Amount Corporate HM Revenue and Customs`';
								elseif ($currency_type=='hm_revenue_and_customs')
									$order='`Invoice Total Amount Corporate`';
								else
									$order='`Invoice Total Amount`';


							} else if ($order=='tax_number')
								$order='`Customer Tax Number`';
							else if ($order=='send_to') {

									$order="`Country Code`$order_direction ,`Invoice Customer Name` $order_direction";
									$order_direction='';
								}



							$corporate_currency='GBP';
						$sql=sprintf("select `Account Currency` from `Account Dimension` ");
					$res=mysql_query($sql);
				if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
					$corporate_currency=$row['Account Currency'];
				}

			$sql="select `Invoice Currency`,`Invoice Total Amount`*`Invoice Currency Exchange` as `Invoice Total Amount Corporate` ,   (select `Exchange` from kbase.`HM Revenue and Customs Currency Exchange Dimension` `HM E` where DATE_FORMAT(`HM E`.`Date`,'%%m%%Y')  =DATE_FORMAT(`Invoice Date`,'%%m%%Y') and `Currency Pair`=Concat(`Invoice Currency`,'GBP') limit 1  )*`Invoice Total Amount` as `Invoice Total Amount Corporate HM Revenue and Customs` ,              `Customer Tax Number`,`European Union`,`Invoice Delivery Country 2 Alpha Code`,`Country Name`,`Country Code`, `Invoice Total Net Amount`,`Invoice Has Been Paid In Full`,`Invoice Key`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice Public ID`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Date`,`Invoice Total Amount`  from `Invoice Dimension`  left join `Customer Dimension` on (`Invoice Customer Key`=`Customer Key`)  left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
		//print $sql;

		$data=array();


	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($currency_type=='original') {
			$total_amount=money($row['Invoice Total Amount'],$row['Invoice Currency']);
		}
		elseif ($currency_type=='corparate_currency') {
			$total_amount=money($row['Invoice Total Amount Corporate'],$corporate_currency);

		}
		elseif ($currency_type=='hm_revenue_and_customs') {
			if ($row['Invoice Total Amount Corporate HM Revenue and Customs']=='')
				$total_amount=_('No FX Data');
			else
				$total_amount=money($row['Invoice Total Amount Corporate HM Revenue and Customs'],'GBP');
		}


		$order_id=sprintf('<a href="invoice.php?id=%d">%s</a>',$row['Invoice Key'],$row['Invoice Public ID']);
		$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Invoice Customer Key'],$row['Invoice Customer Name']);
		//   if($row['Customer Tax Number']!='')
		//  $customer.='<br/>'.$row['Customer Tax Number'];
		if ($row['Invoice Has Been Paid In Full'])
			$state=_('Paid');
		else
			$state=_('No Paid');

		$send_to=sprintf('<span style="font-family:courier">%s</span> <img style="vertical-align:baseline;height:10px;position:relative;top:1px"  src="art/flags/%s.gif" alt="(%s)" title="%s" />',$row['Country Code'],strtolower($row['Invoice Delivery Country 2 Alpha Code']),$row['Country Code'],$row['Country Name']);
		if ($row['European Union']=='Yes') {
			$send_to.=' <img style="vertical-align:baseline;height:10px;position:relative;top:1px"  src="art/flags/eu.gif" title="'._('European Union Member').'" >';
		}

		$data[]=array(
			'id'=>$order_id
			,'customer'=>$customer
			,'tax_number'=>$row['Customer Tax Number']
			,'date'=>strftime("%e %b %y", strtotime($row['Invoice Date']))
			,'total_amount'=>$total_amount

			,'send_to'=>$send_to
			,'state'=>$state
			,'orders'=>$row['Invoice XHTML Orders']
			,'dns'=>$row['Invoice XHTML Delivery Notes']
		);
	}
	mysql_free_result($res);




	if ($total<=$number_results  and $total>1) {
		$data[]=array(
			'id'=>''
			,'customer'=>''
			,'date'=>''
			,'total_amount'=>''
			,'net'=>''
			,'state'=>''
			,'orders'=>''
			,'dns'=>''
		);


	} else {
		$data[]=array();
	}

	$total_records=ceil($total/$number_results)+$total;
	$number_results++;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function tax_overview_europe($country) {


	$conf=$_SESSION['state']['report_sales_with_no_tax']['overview'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr']-1;

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=stripslashes($_REQUEST['where']);
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_sales_with_no_tax']['from']=$from;

	}else {

		$from=$_SESSION['state']['report_sales_with_no_tax']['from'];

	}

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_sales_with_no_tax']['to']=$to;
	}else {

		$to=$_SESSION['state']['report_sales_with_no_tax']['to'];

	}


	if (isset( $_REQUEST['currency_type'])) {
		$currency_type=$_REQUEST['currency_type'];
		$_SESSION['state']['report_sales_with_no_tax']['currency_type']=$currency_type;
	} else {

		$currency_type=$_SESSION['state']['report_sales_with_no_tax']['currency_type'];

	}




	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	//print $where;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$stores=$_SESSION['state']['report_sales_with_no_tax']['stores'];




	$_SESSION['state']['report_sales_with_no_tax']['overview']['order']=$order;
	$_SESSION['state']['report_sales_with_no_tax']['overview']['order_dir']=$order_direction;
	$_SESSION['state']['report_sales_with_no_tax']['overview']['nr']=$number_results;
	$_SESSION['state']['report_sales_with_no_tax']['overview']['sf']=$start_from;
	$_SESSION['state']['report_sales_with_no_tax']['overview']['where']=$where;
	$_SESSION['state']['report_sales_with_no_tax']['overview']['f_field']=$f_field;
	$_SESSION['state']['report_sales_with_no_tax']['overview']['f_value']=$f_value;



	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['report_sales_with_no_tax']['from'],$_SESSION['state']['report_sales_with_no_tax']['to']);
	} else {
		$_SESSION['state']['report_sales_with_no_tax']['from']=$date_interval['from'];
		$_SESSION['state']['report_sales_with_no_tax']['to']=$date_interval['to'];
	}


	// $where=sprintf(" where   `Invoice Date`>=%s and   `Invoice Date`<=%s   "
	//  ,prepare_mysql($date_interval['from'].' 00:00:00')
	//  ,prepare_mysql($date_interval['to'].' 23:59:59')
	// );


	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where.=$where_interval['mysql'];



	$rtext='';
	$filter_msg='';
	$rtext_rpp='';



	global $corporate_currency;




	$sum_net=0;
	$sum_tax=0;
	$sum_total=0;
	$sum_invoices=0;
	$records=1;

	//   $sql="select `Invoice Tax Code`,`Invoice Currency`,sum(`Invoice Total Amount`*`Invoice Currency Exchange` as `Invoice Total Amount Corporate`) ,  sum( (select `Exchange` from kbase.`HM Revenue and Customs Currency Exchange Dimension` `HM E` where DATE_FORMAT(`HM E`.`Date`,'%%m%%Y')  =DATE_FORMAT(`Invoice Date`,'%%m%%Y') and `Currency Pair`=Concat(`Invoice Currency`,'GBP') limit 1  )*`Invoice Total Amount`) as `Invoice Total Amount Corporate HM Revenue and Customs`,sum( `Invoice Total Net Amount`) as net,sum(`Invoice Total Amount`) as total  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  $where  $where_extra group by  `Invoice Tax Code` ";

	if ($country=='GB') {
		$country_label='GB+IM';
		$region='GBIM';
		$where_extra=' and `Invoice Billing Country 2 Alpha Code` in ("GB","IM")';
	}else {
		$where_extra=sprintf(' and `Invoice Billing Country 2 Alpha Code`=%s',prepare_mysql($country));
		$country_label=$country;
		$region=$country;
	}


	$sql="select `Tax Category Name`,count(distinct `Invoice Key`)as invoices,`Invoice Tax Code`,sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total_hq ,sum( `Invoice Total Net Amount`*`Invoice Currency Exchange`) as net_hq,sum( `Invoice Total Tax Amount`*`Invoice Currency Exchange`) as tax_hq  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)  $where  $where_extra group by  `Invoice Tax Code` ";


	$data=array();
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$records++;
		$sum_net+=$row['net_hq'];
		$sum_tax+=$row['tax_hq'];
		$sum_total+=$row['total_hq'];
		$sum_invoices+=$row['invoices'];
		$data[]=array(
			'tax_code'=>$row['Invoice Tax Code'].' ('.$row['Tax Category Name'].')',
			'category'=>$country_label,
			'net'=>money($row['net_hq'],$corporate_currency),
			'tax'=>money($row['tax_hq'],$corporate_currency),
			'total'=>money($row['total_hq'],$corporate_currency),
			'invoices'=>sprintf('<a href="report_sales_with_no_tax.php?view=invoices&tax_category=%s&regions=%s">%s</a>',

				$row['Invoice Tax Code'],$region,
				number($row['invoices']))
		);
	}
	mysql_free_result($res);


	if ($country=='GB') {
		$where_extra=' and `Invoice Billing Country 2 Alpha Code` not in ("GB","IM") and `European Union`="Yes" ';
	}else {
		$where_extra=sprintf(' and  `European Union`="Yes"  and `Invoice Billing Country 2 Alpha Code`!=%s',prepare_mysql($country));
	}


	$sql="select  `Tax Category Name`,count(distinct `Invoice Key`)as invoices,`Invoice Tax Code`,sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total_hq ,sum( `Invoice Total Net Amount`*`Invoice Currency Exchange`) as net_hq,sum( `Invoice Total Tax Amount`*`Invoice Currency Exchange`) as tax_hq  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`) left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`) $where  $where_extra group by  `Invoice Tax Code` ";
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$records++;
		$sum_net+=$row['net_hq'];
		$sum_tax+=$row['tax_hq'];
		$sum_total+=$row['total_hq'];
		$sum_invoices+=$row['invoices'];

		$data[]=array(
			'tax_code'=>$row['Invoice Tax Code'].' ('.$row['Tax Category Name'].')',
			'category'=>'EU (no '.$country.')',
			'net'=>money($row['net_hq'],$corporate_currency),
			'tax'=>money($row['tax_hq'],$corporate_currency),
			'total'=>money($row['total_hq'],$corporate_currency),
			'invoices'=>sprintf('<a href="report_sales_with_no_tax.php?view=invoices&tax_category=%s&regions=%s">%s</a>',

				$row['Invoice Tax Code'],$region,

				number($row['invoices'])
			)
		);
	}
	mysql_free_result($res);

	if ($country=='GB') {
		$where_extra=' and `Invoice Billing Country 2 Alpha Code` not in ("GB","IM") and `European Union`="No" ';

	}else {
		$where_extra=sprintf(' and  `European Union`="No"  and `Invoice Billing Country 2 Alpha Code`!=%s',prepare_mysql($country));
	}



	$sql="select  `Tax Category Name`,count(distinct `Invoice Key`)as invoices,`Invoice Tax Code`,sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total_hq ,sum( `Invoice Total Net Amount`*`Invoice Currency Exchange`) as net_hq,sum( `Invoice Total Tax Amount`*`Invoice Currency Exchange`) as tax_hq  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)  $where  $where_extra group by  `Invoice Tax Code` ";

	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$records++;
		$sum_net+=$row['net_hq'];
		$sum_tax+=$row['tax_hq'];
		$sum_total+=$row['total_hq'];
		$sum_invoices+=$row['invoices'];

		$data[]=array(
			'tax_code'=>$row['Invoice Tax Code'].' ('.$row['Tax Category Name'].')',
			'category'=>'no EU',
			'net'=>money($row['net_hq'],$corporate_currency),
			'tax'=>money($row['tax_hq'],$corporate_currency),
			'total'=>money($row['total_hq'],$corporate_currency),
			'invoices'=>sprintf('<a href="report_sales_with_no_tax.php?view=invoices&tax_category=%s&regions=%s">%s</a>',

				$row['Invoice Tax Code'],'NOEU',

				number($row['invoices'])
			)
		);
	}
	mysql_free_result($res);

	$data[]=array(
		'tax_category'=>'',
		'category'=>'',
		'net'=>money($sum_net,$corporate_currency),
		'tax'=>money($sum_tax,$corporate_currency),
		'total'=>money($sum_total,$corporate_currency),
		'invoices'=>number($sum_invoices),
	);


	$number_categories=$records-1;
	$rtext=number($number_categories).' '.ngettext(_('category'),_('categories'),$number_categories);
	$rtext_rpp=' ('._('Showing all').')';
	$total_records=$records;
	$number_results++;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>'category',
			'sort_dir'=>'',
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function list_customers_by_tax_europe($country) {

	global $corporate_currency;
	$conf=$_SESSION['state']['report_sales_with_no_tax']['customers'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr']-1;

		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	
		
	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_sales_with_no_tax']['from']=$from;

	}else {

		$from=$_SESSION['state']['report_sales_with_no_tax']['from'];

	}

	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_sales_with_no_tax']['to']=$to;
	}else {

		$to=$_SESSION['state']['report_sales_with_no_tax']['to'];

	}


	if (isset( $_REQUEST['currency_type'])) {
		$currency_type=$_REQUEST['currency_type'];
		$_SESSION['state']['report_sales_with_no_tax']['currency_type']=$currency_type;
	} else {

		$currency_type=$_SESSION['state']['report_sales_with_no_tax']['currency_type'];

	}




	$elements_region=$_SESSION['state']['report_sales_with_no_tax'][$country]['regions'];
	//print_r($elements_region);
	foreach ($elements_region as $element_region=>$value) {


		if (isset( $_REQUEST['elements_region_'.$element_region.'_customers'])) {
			$elements_region[$element_region]=$_REQUEST['elements_region_'.$element_region.'_customers'];
		}

	}


	$_SESSION['state']['report_sales_with_no_tax'][$country]['regions']=$elements_region;




	$elements_tax_category=$_SESSION['state']['report_sales_with_no_tax'][$country]['tax_category'];


	foreach ($elements_tax_category as $key=>$value) {
		if (isset( $_REQUEST['elements_tax_category_'.$key.'_customers'])) {
			$elements_tax_category[$key]=$_REQUEST['elements_tax_category_'.$key.'_customers'];
		}

	}


	$_SESSION['state']['report_sales_with_no_tax'][$country]['tax_category']=$elements_tax_category;



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	//print $where;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$stores=$_SESSION['state']['report_sales_with_no_tax']['stores'];

	$_SESSION['state']['report_sales_with_no_tax']['customers']['order']=$order;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['order_dir']=$order_direction;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['nr']=$number_results;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['sf']=$start_from;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['f_field']=$f_field;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['f_value']=$f_value;




include_once('splinters/customers_by_tax_europe_prepare_list.php');



	$sql="select  count(Distinct `Invoice Customer Key`) as total from `Invoice Dimension` left join `Customer Dimension` on (`Invoice Customer Key`=`Customer Key`) left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)  $where $wheref  ";
	// print $sql ;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(Distinct `Invoice Customer Key`) as total  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)    $where  ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($res);
	}
	$rtext=number($total_records)." ".ngettext('customer','customers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_("Showing all customers");

	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
		break;
	case('customer_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
		break;
	case('minvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
		break;
	case('maxvalue'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order maximum value of")." <b>".money($f_value)."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with max value of')." <b>".money($f_value)."*</b>)";
		break;
	case('max'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
		break;
	}




	$_order=$order;
	$_dir=$order_direction;

	if ($order=='name')
		$order='`Customer Name`';
	else if ($order=='total_amount') {
			if ($currency_type=='hm_revenue_and_customs')
				$order='`Invoice Total Amount Corporate HM Revenue and Customs`';
			elseif ($currency_type=='hm_revenue_and_customs')
				$order='`Invoice Total Amount Corporate`';
			else
				$order='`Invoice Total Amount`';


		} else if ($order=='customer')
			$order='`Invoice Customer Name`';
		else if ($order=='tax_number')
				$order='`Customer Tax Number`';
			else if ($order=='send_to')
					$order='`Country Code`';
				else if ($order=='num_invoices')
						$order='`Invoices`';
					else
						$order='`Customer Name`';




					$sql="select `Invoice Billing Country 2 Alpha Code`,sum(`Invoice Total Tax Amount`*`Invoice Currency Exchange`) as tax_hq,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as net_hq, sum( (select `Exchange` from kbase.`HM Revenue and Customs Currency Exchange Dimension` `HM E` where DATE_FORMAT(`HM E`.`Date`,'%%m%%Y')  =DATE_FORMAT(`Invoice Date`,'%%m%%Y') and `Currency Pair`=Concat(`Invoice Currency`,'GBP') limit 1  )*`Invoice Total Amount`) as `Invoice Total Amount Corporate HM Revenue and Customs`  ,  `Invoice Currency`,`Customer Tax Number`,`European Union`,`Invoice Delivery Country 2 Alpha Code`,count(distinct `Invoice Key`) as `Invoices` ,`Country Name`,`Country Code`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice Date`,sum(`Invoice Total Amount`) as `Invoice Total Amount`,sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total_hq  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`) left join `Customer Dimension` on (`Invoice Customer Key`=`Customer Key`) $where $wheref  group by `Invoice Customer Key` order by $order $order_direction limit $start_from,$number_results ";
				//print $sql;

				$data=array();


			$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




			$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Invoice Customer Key'],$row['Invoice Customer Name']);
			//   if($row['Customer Tax Number']!='')
			//  $customer.='<br/>'.$row['Customer Tax Number'];





			$send_to=sprintf('<span style="font-family:courier">%s</span> <img style="vertical-align:baseline;height:10px;position:relative;top:1px"  src="art/flags/%s.gif" alt="(%s)" title="%s" />',$row['Country Code'],strtolower($row['Invoice Delivery Country 2 Alpha Code']),$row['Country Code'],$row['Country Name']);
			if ($row['European Union']=='Yes') {
				$send_to.=' <img style="vertical-align:baseline;height:10px;position:relative;top:1px"  src="art/flags/eu.gif" title="'._('European Union Member').'" >';
			}

			if ($currency_type=='original') {
				$total_amount=money($row['Invoice Total Amount'],$row['Invoice Currency']);
			}
			elseif ($currency_type=='corparate_currency') {
				$total_amount=money($row['total_hq'],$corporate_currency);

			}
			elseif ($currency_type=='hm_revenue_and_customs') {
				if ($row['Invoice Total Amount Corporate HM Revenue and Customs']=='')
					$total_amount=_('No FX Data');
				else
					$total_amount=money($row['Invoice Total Amount Corporate HM Revenue and Customs'],'GBP');
			}

			$data[]=array(

				'name'=>$customer,
				'tax_number'=>$row['Customer Tax Number'],
				'date'=>strftime("%e %b %y", strtotime($row['Invoice Date'])),
				'total_amount'=>money($row['total_hq'],$corporate_currency),
				'net_hq'=>money($row['net_hq'],$corporate_currency),
				'tax_hq'=>money($row['tax_hq'],$corporate_currency),

				'send_to'=>$send_to,
				'num_invoices'=>number($row['Invoices'])

			);
		}
	mysql_free_result($res);




	if ($total<=$number_results  and $total>1) {
		$data[]=array(
			'id'=>''
			,'customer'=>''
			,'date'=>''
			,'total_amount'=>''
			,'net'=>''
			,'state'=>''
			,'orders'=>''
			,'dns'=>''
		);


	} else {
		$data[]=array();
	}



	$total_records=ceil($total/$number_results)+$total;
	$number_results++;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}


function list_country_sales() {
	$conf=$_SESSION['state']['report_geo_sales']['countries'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {

		$from=$_SESSION['state']['report_geo_sales']['from'];

	}

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else {

		$to=$_SESSION['state']['report_geo_sales']['to'];

	}



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$_SESSION['state']['report_geo_sales']['countries']['order']=$order;
	$_SESSION['state']['report_geo_sales']['countries']['order_dir']=$order_direction;
	$_SESSION['state']['report_geo_sales']['countries']['nr']=$number_results;
	$_SESSION['state']['report_geo_sales']['countries']['sf']=$start_from;
	$_SESSION['state']['report_geo_sales']['countries']['where']=$where;
	$_SESSION['state']['report_geo_sales']['countries']['f_field']=$f_field;
	$_SESSION['state']['report_geo_sales']['countries']['f_value']=$f_value;

	$mode=$_SESSION['state']['report_geo_sales']['mode'];
	$mode_key=$_SESSION['state']['report_geo_sales']['mode_key'];
	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval['mysql']='';
	} else {
		$_SESSION['state']['report_geo_sales']['countries']['from']=$date_interval['from'];
		$_SESSION['state']['report_geo_sales']['countries']['to']=$date_interval['to'];
	}


	$where=sprintf(' %s ',$date_interval['mysql']);

	$where_geo=' where true ';
	if ($mode=='continent') {
		$where_geo=sprintf(" where  `Continent Code`=%s",prepare_mysql($mode_key));
	}elseif ($mode=='wregion') {
		$where_geo=sprintf(" where  `World Region Code`=%s",prepare_mysql($mode_key));
	}


	$filter_msg='';
	$wheref='';


	if ($f_field=='country_code' and $f_value!='')
		$wheref.=" and  `Country Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='wregion_code' and $f_value!='')
		$wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='continent_code' and $f_value!='')
		$wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from kbase.`Country Dimension`  $where_geo $wheref  ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from kbase.`Country Dimension`  $where_geo   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($res);
	}


	$rtext=number($total_records)." ".ngettext('Country','Countries',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_('(Showing all)');


	$filter_msg='';

	switch ($f_field) {
	case('country_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any country with code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('countries with code like')." <b>$f_value</b>)";
		break;
	case('wregion_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
		break;
	case('continent_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
		break;
	}





	$_order=$order;
	$_dir=$order_direction;



	if ($order=='population')
		$order='`Country Population`';
	elseif ($order=='gnp')
		$order='`Country GNP`';
	else
		$order='`Country Name`';




	$adata=array();
	$sql="select  `World Region Code`,`World Region`,`Country GNP`,`Country Population`,`Country Code`,`Country Name`,`Country 2 Alpha Code` from kbase.`Country Dimension`  $where_geo $wheref ";

	//print $sql;
	$res=mysql_query($sql);
	$i=0;
	while ($row=mysql_fetch_array($res)) {
		$wregion=sprintf('<a href="report_geo_sales.php?wregion=%s">%s</a>',$row['World Region Code'],$row['World Region']);
		$country_name=sprintf('<a href="report_geo_sales.php?country=%s">%s</a>',$row['Country Code'],$row['Country Name']);
		$country_code=sprintf('<a href="report_geo_sales.php?country=%s">%s</a>',$row['Country Code'],$row['Country Code']);
		$country_flag=sprintf('<img  src="art/flags/%s.gif" alt="">',strtolower($row['Country 2 Alpha Code']));

		if ($row['Country Population']<100000) {
			$population='>0.1M';
		} else {
			$population=number($row['Country Population']/1000000,1).'M';
		}
		if ($row['Country GNP']=='')
			$gnp='ND';
		elseif ($row['Country GNP']<1000)
			$gnp='$'.number($row['Country GNP'],0);
		else
			$gnp='$'.number($row['Country GNP']/1000,0).'k';

		$adata[]=array(
			'name'=>$country_name,
			'code'=>$country_code,
			'_name'=>$row['Country Name'],
			'_code'=>$row['Country Code'],
			'flag'=>$country_flag,
			'population'=>$population,
			'gnp'=>$gnp,
			'wregion'=>$wregion,
			'sales'=>floatval(0),
			'sales_formated'=>money(0),
			'invoices'=>floatval(0),
			'invoices_formated'=>number(0),
		);

		$index_array[$row['Country 2 Alpha Code']]=$i;
		$i++;


	}
	mysql_free_result($res);


	//print_r($adata);

	//print_r($index_array);


	$sql="select `Invoice Billing Country 2 Alpha Code`,count(*) as invoices,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as sales from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  $where_geo    $where $wheref group by  `Invoice Billing Country 2 Alpha Code`";
	//print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		if (array_key_exists($row['Invoice Billing Country 2 Alpha Code'],$index_array)) {
			$index=$index_array[$row['Invoice Billing Country 2 Alpha Code']];
			$adata[$index]['sales']=floatval($row['sales']);
			$adata[$index]['sales_formated']=money($row['sales']);
			$adata[$index]['invoices']=floatval($row['invoices']);
			$adata[$index]['invoices_formated']=number($row['invoices']);
		}
	}
	mysql_free_result($res);



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$total,
			'records_perpage'=>$number_results,
			// 'records_text'=>$rtext,
			// 'records_order'=>$order,
			// 'records_order_dir'=>$order_dir,
			// 'filtered'=>$filtered,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
		)
	);

	echo json_encode($response);
}

function list_wregion_sales() {
	$conf=$_SESSION['state']['report_geo_sales']['wregions'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {

		$from=$_SESSION['state']['report_geo_sales']['from'];

	}

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else {

		$to=$_SESSION['state']['report_geo_sales']['to'];

	}

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';


	$_SESSION['state']['report_geo_sales']['wregions']['order']=$order;
	$_SESSION['state']['report_geo_sales']['wregions']['order_dir']=$order_direction;
	$_SESSION['state']['report_geo_sales']['wregions']['nr']=$number_results;
	$_SESSION['state']['report_geo_sales']['wregions']['sf']=$start_from;
	$_SESSION['state']['report_geo_sales']['wregions']['where']=$where;
	$_SESSION['state']['report_geo_sales']['wregions']['f_field']=$f_field;
	$_SESSION['state']['report_geo_sales']['wregions']['f_value']=$f_value;

	$mode=$_SESSION['state']['report_geo_sales']['mode'];
	$mode_key=$_SESSION['state']['report_geo_sales']['mode_key'];

	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval['mysql']='';
	} else {
		$_SESSION['state']['report_geo_sales']['countries']['from']=$date_interval['from'];
		$_SESSION['state']['report_geo_sales']['countries']['to']=$date_interval['to'];
	}


	$where=sprintf(' %s ',$date_interval['mysql']);

	$where_geo=' where true ';
	if ($mode=='continent') {
		$where_geo=sprintf(" where  `Continent Code`=%s",prepare_mysql($mode_key));
	}


	$filter_msg='';
	$wheref='';


	if ($f_field=='wregion_code' and $f_value!='')
		$wheref.=" and  `World Region Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='continent_code' and $f_value!='')
		$wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";

	$sql="select count(Distinct  `World Region Code`) as total from kbase.`Country Dimension` $where_geo  $wheref  ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(Distinct  `World Region Code`) as total from kbase.`Country Dimension`  $where_geo  ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($res);
	}


	$rtext=number($total_records)." ".ngettext('Region','Regions',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_('(Showing all)');


	$filter_msg='';

	switch ($f_field) {

	case('wregion_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any world region with code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('regions with code like')." <b>$f_value</b>)";
		break;
	case('continent_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
		break;
	}







	$_order=$order;
	$_dir=$order_direction;


	if ($order=='wregion_code' )
		$order='`World Region Code`';
	elseif ($order=='population' )
		$order='`Population`';
	elseif ($order=='gnp' )
		$order='`GNP`';
	else
		$order='`World Region`';






	$index_array=array();
	$adata=array();
	$sql="select  count(*) as Countries,sum(`Country GNP`) as GNP,sum(`Country Population`) as Population, `World Region`,`World Region Code` from kbase.`Country Dimension` $where_geo $wheref group by `World Region Code` ";

	//print $sql;
	$res=mysql_query($sql);
	$i=0;
	while ($row=mysql_fetch_array($res)) {
		$wregion_name=sprintf('<a href="report_geo_sales.php?wregion=%s">%s</a>',$row['World Region Code'],$row['World Region']);
		$wregion_code=sprintf('<a href="report_geo_sales.php?wregion=%s">%s</a>',$row['World Region Code'],$row['World Region Code']);
		if ($row['Population']<100000) {
			$population='>0.1M';
		} else {
			$population=number($row['Population']/1000000,1).'M';
		}
		if ($row['GNP']=='')
			$gnp='ND';
		elseif ($row['GNP']<1000)
			$gnp='$'.number($row['GNP'],0);
		else
			$gnp='$'.number($row['GNP']/1000,0).'k';

		$adata[]=array(
			'wregion_name'=>$wregion_name,
			'wregion_code'=>$wregion_code,
			'countries'=>number($row['Countries']),
			'population'=>$population,
			'gnp'=>$gnp,
			'sales'=>floatval(0),
			'sales_formated'=>money(0),
			'invoices'=>floatval(0),
			'invoices_formated'=>number(0),
		);
		$index_array[$row['World Region Code']]=$i;
		$i++;
	}
	mysql_free_result($res);

	$sql="select `World Region Code`,count(*) as invoices,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as sales from `Invoice Dimension`  left join kbase.`Country Dimension` on (`Invoice Billing Country 2 Alpha Code`=`Country 2 Alpha Code`) $where_geo  $where $wheref group by  `World Region Code`";
	// print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		if (array_key_exists($row['World Region Code'],$index_array)) {
			$index=$index_array[$row['World Region Code']];
			$adata[$index]['sales']=floatval($row['sales']);
			$adata[$index]['sales_formated']=money($row['sales']);
			$adata[$index]['invoices']=floatval($row['invoices']);
			$adata[$index]['invoices_formated']=number($row['invoices']);
		}
	}
	mysql_free_result($res);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$total,
			'records_perpage'=>$number_results,
			// 'records_text'=>$rtext,
			// 'records_order'=>$order,
			// 'records_order_dir'=>$order_dir,
			// 'filtered'=>$filtered,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
		)
	);

	echo json_encode($response);
}


function list_continent_sales() {
	$conf=$_SESSION['state']['report_geo_sales']['continents'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {

		$from=$_SESSION['state']['report_geo_sales']['from'];

	}

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else {

		$to=$_SESSION['state']['report_geo_sales']['to'];

	}

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';


	$_SESSION['state']['report_geo_sales']['continents']['order']=$order;
	$_SESSION['state']['report_geo_sales']['continents']['order_dir']=$order_direction;
	$_SESSION['state']['report_geo_sales']['continents']['nr']=$number_results;
	$_SESSION['state']['report_geo_sales']['continents']['sf']=$start_from;
	$_SESSION['state']['report_geo_sales']['continents']['where']=$where;
	$_SESSION['state']['report_geo_sales']['continents']['f_field']=$f_field;
	$_SESSION['state']['report_geo_sales']['continents']['f_value']=$f_value;


	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval['mysql']='';
	}

	$where=sprintf('where true %s ',$date_interval['mysql']);


	$filter_msg='';
	$wheref='';

	if ($f_field=='continent_code' and $f_value!='')
		$wheref.=" and  `Continent Code` like '".addslashes($f_value)."%'";

	$sql="select count(Distinct  `Continent Code`) as total from kbase.`Country Dimension`  $wheref  ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(Distinct  `Continent Code`) as total from kbase.`Country Dimension`    ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($res);
	}


	$rtext=number($total_records)." ".ngettext('Continent','Continents',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=_('(Showing all)');


	$filter_msg='';

	switch ($f_field) {


	case('continent_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any continent with code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('continents with code like')." <b>$f_value</b>)";
		break;
	}







	$_order=$order;
	$_dir=$order_direction;


	if ($order=='continent_code' )
		$order='`Continent Code`';
	elseif ($order=='population' )
		$order='`Population`';
	elseif ($order=='gnp' )
		$order='`GNP`';
	else
		$order='`World Region`';






	$index_array=array();
	$adata=array();
	$sql="select  count(*) as Countries,sum(`Country GNP`) as GNP,sum(`Country Population`) as Population, `World Region`,`Continent Code`,`Continent` from kbase.`Country Dimension`  $wheref group by `Continent Code` ";

	// print $sql;
	$res=mysql_query($sql);
	$i=0;
	while ($row=mysql_fetch_array($res)) {
		$continent_name=sprintf('<a href="report_geo_sales.php?continent=%s">%s</a>',$row['Continent Code'],$row['Continent']);
		$continent_code=sprintf('<a href="report_geo_sales.php?continent=%s">%s</a>',$row['Continent Code'],$row['Continent Code']);
		if ($row['Population']<100000) {
			$population='>0.1M';
		} else {
			$population=number($row['Population']/1000000,1).'M';
		}
		if ($row['GNP']=='')
			$gnp='ND';
		elseif ($row['GNP']<1000)
			$gnp='$'.number($row['GNP'],0);
		else
			$gnp='$'.number($row['GNP']/1000,0).'k';

		$adata[]=array(
			'continent_name'=>$continent_name,
			'continent_code'=>$continent_code,
			'countries'=>number($row['Countries']),
			'population'=>$population,
			'gnp'=>$gnp,
			'sales'=>floatval(0),
			'sales_formated'=>money(0),
			'invoices'=>floatval(0),
			'invoices_formated'=>number(0),
		);
		$index_array[$row['Continent Code']]=$i;
		$i++;
	}
	mysql_free_result($res);

	$sql="select `Continent Code`,count(*) as invoices,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as sales from `Invoice Dimension`  left join kbase.`Country Dimension` on (`Invoice Billing Country 2 Alpha Code`=`Country 2 Alpha Code`)   $where $wheref group by  `Continent Code`";
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res)) {
		if (array_key_exists($row['Continent Code'],$index_array)) {
			$index=$index_array[$row['Continent Code']];
			$adata[$index]['sales']=floatval($row['sales']);
			$adata[$index]['sales_formated']=money($row['sales']);
			$adata[$index]['invoices']=floatval($row['invoices']);
			$adata[$index]['invoices_formated']=number($row['invoices']);
		}
	}
	mysql_free_result($res);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$total,
			'records_perpage'=>$number_results,
			// 'records_text'=>$rtext,
			// 'records_order'=>$order,
			// 'records_order_dir'=>$order_dir,
			// 'filtered'=>$filtered,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp
		)
	);

	echo json_encode($response);
}

function list_sales_components_per_store() {
	global $user,$corporate_currency;
	$conf=$_SESSION['state']['report_sales_components']['stores'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	/*
	if (isset( $_REQUEST['exchange_type']))
		$exchange_type=addslashes($_REQUEST['exchange_type']);
	else
		$exchange_type=$conf['exchange_type'];

	if (isset( $_REQUEST['exchange_value']))
		$exchange_value=addslashes($_REQUEST['exchange_value']);
	else
		$exchange_value=$conf['exchange_value'];

	if (isset( $_REQUEST['show_default_currency']))
		$show_default_currency=addslashes($_REQUEST['show_default_currency']);
	else
		$show_default_currency=$conf['show_default_currency'];


*/

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$percentages=false;

	/*
	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
		$_SESSION['state']['report_sales_components']['stores']['percentages']=$percentages;
	} else
		$percentages=$_SESSION['state']['report_sales_components']['stores']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['report_sales_components']['stores']['period']=$period;
	} else
		$period=$_SESSION['state']['report_sales_components']['stores']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
		$_SESSION['state']['report_sales_components']['stores']['avg']=$avg;
	} else
		$avg=$_SESSION['state']['report_sales_components']['stores']['avg'];
*/



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_sales_components']['from']=$from;

	}else
		$from=$_SESSION['state']['report_sales_components']['from'];
	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_sales_components']['to']=$to;
	}else
		$to=$_SESSION['state']['report_sales_components']['to'];




	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Invoice Date`');



	//$_SESSION['state']['report_sales_components']['stores']['exchange_type']=$exchange_type;
	//$_SESSION['state']['report_sales_components']['stores']['exchange_value']=$exchange_value;
	//$_SESSION['state']['report_sales_components']['stores']['show_default_currency']=$show_default_currency;
	$_SESSION['state']['report_sales_components']['stores']['order']=$order;
	$_SESSION['state']['report_sales_components']['stores']['order_dir']=$order_dir;
	$_SESSION['state']['report_sales_components']['stores']['nr']=$number_results;
	$_SESSION['state']['report_sales_components']['stores']['sf']=$start_from;
	//$_SESSION['state']['report_sales_components']['stores']['where']=$where;
	$_SESSION['state']['report_sales_components']['stores']['f_field']=$f_field;
	$_SESSION['state']['report_sales_components']['stores']['f_value']=$f_value;

	$where=sprintf("where S.`Store Key` in (%s)",join(',',$user->stores));
	$filter_msg='';
	$wheref=wheref_stores($f_field,$f_value);

	$sql="select count(*) as total from `Store Dimension`  S $where $wheref";
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Store Dimension` S  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;





	if ($order=='code')
		$order='`Store Code`';
	elseif ($order=='name')
		$order='`Store Name`';
	else
		$order='`Store Code`';


	$sum_total_items=0;
	$sum_total_shipping=0;
	$sum_total_tax=0;
	$sum_total_charges=0;
	$sum_total_total=0;
	$sum_total_bonus_value=0;


	$_adata=array();
	$sql="select *  from `Store Dimension` S    $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	$res = mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);
		$_adata[$row['Store Key']]=array(
			'code'=>$code,
			'name'=>$name,
			'items'=>money(0,$corporate_currency),
			'shipping'=>money(0,$corporate_currency),
			'tax'=>money(0,$corporate_currency),
			'charges'=>money(0,$corporate_currency),
			'total'=>money(0,$corporate_currency),
			'bonus_value'=>money(0,$corporate_currency),

		);
	}
	mysql_free_result($res);

	$sql="select S.`Store Key`,
	sum(`Invoice Items Net Amount`) as items ,
	sum(`Invoice Shipping Net Amount`) as shipping ,
	sum(`Invoice Total Tax Amount`) as tax ,
	sum(`Invoice Items Net Amount`) as total ,
	sum(`Invoice Bonus Amount Value`) as bonus_value ,
	sum(`Invoice Total Amount`) as charges

	from `Invoice Dimension` I left join `Store Dimension` S on (`Store Key`=`Invoice Store Key`) $where $wheref ".$date_interval['mysql']." group by I.`Invoice Store Key`    ";
	//print $sql;
	$res = mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$sum_total_items+=$row['items'];
		$sum_total_shipping+=$row['shipping'];
		$sum_total_tax+=$row['tax'];
		$sum_total_total+=$row['total'];
		$sum_total_bonus_value+=$row['bonus_value'];
		$sum_total_charges+=$row['charges'];

		$_adata[$row['Store Key']]['items']=money($row['items'],$corporate_currency);
		$_adata[$row['Store Key']]['shipping']=money($row['shipping'],$corporate_currency);
		$_adata[$row['Store Key']]['total']=money($row['total'],$corporate_currency);
		$_adata[$row['Store Key']]['tax']=money($row['tax'],$corporate_currency);
		$_adata[$row['Store Key']]['charges']=money($row['charges'],$corporate_currency);
		$_adata[$row['Store Key']]['bonus_value']=money($row['bonus_value'],$corporate_currency);


	}
	mysql_free_result($res);


	foreach ($_adata as $value) {
		$adata[]=$value;
	}


	if ($total<=$number_results) {





		$adata[]=array(
			'name'=>'',
			'code'=>_('Total'),
			'items'=>money($sum_total_items,$corporate_currency),
			'shipping'=>money($sum_total_shipping,$corporate_currency),
			'tax'=>money($sum_total_tax,$corporate_currency),
			'charges'=>money($sum_total_charges,$corporate_currency),
			'total'=>money($sum_total_total,$corporate_currency),
			'bonus_value'=>money($sum_total_bonus_value,$corporate_currency),

		);
		$total_records++;
		$number_results++;
	}

	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//$total_records=ceil($total_records/$number_results)+$total_records;
	//$total_records=$total_records;




	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_pending_orders_per_store() {
	global $user,$corporate_currency;
	$conf=$_SESSION['state']['report_pending_orders']['stores'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$percentages=false;

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state']['report_pending_orders']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['report_pending_orders']['to'];

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Date`');

	$_SESSION['state']['report_pending_orders']['stores']['order']=$order;
	$_SESSION['state']['report_pending_orders']['stores']['order_dir']=$order_dir;
	$_SESSION['state']['report_pending_orders']['stores']['nr']=$number_results;
	$_SESSION['state']['report_pending_orders']['stores']['sf']=$start_from;
	$_SESSION['state']['report_pending_orders']['stores']['f_field']=$f_field;
	$_SESSION['state']['report_pending_orders']['stores']['f_value']=$f_value;


	if (count($user->stores)==0) {
		$where=' where false ';
		$where_store=' where false ';
	}else {
		$where=' where `Order Current Dispatch State` not in ("Dispatched","Unknown","Packing","Cancelled","Suspended","")';
		$where.=sprintf(" and O.`Order Store Key` in (%s)",join(',',$user->stores));
		$where_store=sprintf(" where  `Store Key` in (%s)",join(',',$user->stores));
	}

	$filter_msg='';

	$wheref='';
	if ( $f_field=='name' and $f_value!='' )
		$wheref.=" and  `Store Name` like '%".addslashes( $f_value )."%'";
	elseif ( $f_field=='code'  and $f_value!='' )
		$wheref.=" and  `Store Code` like '".addslashes( $f_value )."%'";

	$sql="select count(*) as total from  `Store Dimension` $where_store   $wheref";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from Store Dimension`  $where_store  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);
	}

	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='code')
		$order='`Store Code`';
	elseif ($order=='name')
		$order='`Store Name`';
	else
		$order='`Store Code`';


	$sum_total_orders=0;
	$sum_total_total=0;

	$_adata=array();
	$sql="select `Store Name`,`Store Code`,`Store Key` from `Store Dimension`  $where_store $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	$res = mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="store_pending_orders.php?id=%d">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);
		$_adata[$row['Store Key']]=array(
			'code'=>$code,
			'name'=>$name,
			'orders'=>0,
			'first_date'=>'',
			'total'=>money(0,$corporate_currency),
		);
	}
	mysql_free_result($res);


	//print_r($_adata);

	$sql="select `Order Store Key`,count(*) as orders,sum(`Order Total Amount`*`Order Currency Exchange`) as total ,min(`Order Date`) first_date	from `Order Dimension` O  left join `Store Dimension` on (`Order Store Key`=`Store Key`)  $where $wheref ".$date_interval['mysql']." group by O.`Order Store Key`    ";
	//print $sql;
	$res = mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$sum_total_orders+=$row['orders'];

		$sum_total_total+=$row['total'];
		// print_r($row);

		$_adata[$row['Order Store Key']]['orders']=number($row['orders']);
		$_adata[$row['Order Store Key']]['total']=money($row['total'],$corporate_currency);
		$_adata[$row['Order Store Key']]['first_date']=strftime("%a %e %b %y %H:%M %Z", strtotime($row['first_date']." +00:00"));


	}
	mysql_free_result($res);


	foreach ($_adata as $value) {
		$adata[]=$value;
	}


	if ($total<=$number_results) {
		$adata[]=array(
			'name'=>'',
			'code'=>_('Total'),
			'orders'=>number($sum_total_orders),
			'total'=>money($sum_total_total,$corporate_currency),
		);
		$total_records++;
		$number_results++;
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_intrastat() {

	$conf=$_SESSION['state']['report_intrastat'];

	$conf_table='report_intrastat';


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['to'])) {
		$to=$_REQUEST['to'];
		$_SESSION['state']['report_intrastat']['to']=$to;
	} else
		$to=$_SESSION['state']['report_intrastat']['to'];



	if (isset( $_REQUEST['from'])) {
		$from=$_REQUEST['from'];
		$_SESSION['state']['report_intrastat']['from']=$from;
	} else
		$from= $_SESSION['state']['report_intrastat']['from'];




	$_SESSION['state']['report_intrastat']['order']=$order;
	$_SESSION['state']['report_intrastat']['order_dir']=$order_dir;
	$_SESSION['state']['report_intrastat']['nr']=$number_results;
	$_SESSION['state']['report_intrastat']['sf']=$start_from;
	$_SESSION['state']['report_intrastat']['f_field']=$f_field;
	$_SESSION['state']['report_intrastat']['f_value']=$f_value;

	$_order=$order;
	$_dir=$order_direction;

	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['report_sales_with_no_tax']['from'],$_SESSION['state']['report_sales_with_no_tax']['to']);
	} else {
		$_SESSION['state']['report_intrastat']['from']=$date_interval['from'];
		$_SESSION['state']['report_intrastat']['to']=$date_interval['to'];
	}

	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';


	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');


	$where=sprintf("where `Current Dispatching State`='Dispatched' %s and `Destination Country 2 Alpha Code` in ('AT','BE','BG','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES') ",
		$date_interval['mysql']
	);


	$wheref='';
	if ($f_field=='tariff_code'  and $f_value!='')
		$wheref.=" and `Product Tariff Code` like '".addslashes($f_value)."%'";



	$sql="select `Product Tariff Code` from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  $where $wheref group by `Product Tariff Code`,`Destination Country 2 Alpha Code` ";

	$result=mysql_query($sql);
	//print $sql;


	$total= mysql_num_rows($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select `Product Tariff Code` from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  $where group by `Product Tariff Code`,`Destination Country 2 Alpha Code` ";
		$result=mysql_query($sql);
		$total_records=mysql_num_rows($result);
		$filtered=$total_records-$total;



	}

	$rtext=number($total_records)." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';






	$filter_msg='';

	switch ($f_field) {
	case('tariff_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record with comodity code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('records with comodity code')." <b>$f_value</b>*)";
		break;
	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with name')." <b>$f_value</b>*)";
		break;

	}

	if ($order=='tariff_code')
		$order='`Product Tariff Code`';

	elseif ($order=='value') {
		$order="value";
	}elseif ($order=='weight') {
		$order="weight";
	}elseif ($order=='country_2alpha_code') {
		$order="`Destination Country 2 Alpha Code`, `Product Tariff Code`  ";
	}
	else {
		$order='`Product Tariff Code`';
	}

	$sql="select  sum(`Delivery Note Quantity`*`Product Units Per Case`) as items,sum(`Order Bonus Quantity`) as bonus,GROUP_CONCAT(DISTINCT ' <a href=\"invoice.php?id=',`Invoice Key`,'\">',`Invoice Public ID`,'</a>' ) as invoices ,sum(`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)) as value ,
	sum(`Delivery Note Quantity`*`Product Parts Weight`) as weight ,
	LEFT(`Product Tariff Code`,8) as tariff_code, date_format(`Invoice Date`,'%y%m') as monthyear ,`Destination Country 2 Alpha Code`
	from
	`Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)
	$where $wheref group by `Product Tariff Code`,`Destination Country 2 Alpha Code`  order by   $order $order_dir  limit $start_from,$number_results";
	//print $sql;
	$result=mysql_query($sql);
	$data=array();

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		$invoices=$row['invoices'];

		$data[]=array(
			'tariff_code'=>$row['tariff_code'],
			'items'=>number($row['items']),
			'bonus'=>number($row['bonus']),
			'monthyear'=>$row['monthyear'],
			'value'=>money($row['value']),
			'weight'=>weight($row['weight']),
			'country_2alpha_code'=>$row['Destination Country 2 Alpha Code'],
			'invoices'=>$invoices




		);
	}


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);


}

function out_of_stock_data($data) {

	$from=$data['from'];
	$to=$data['to'];
	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Date`');
	$number_out_of_stock_parts=0;
	$number_out_of_stock_transactions=0;
	$number_parts=0;
	$number_transactions=0;


	$sql=sprintf("select count(DISTINCT `Part SKU`) as number_out_of_stock_parts, count(*) as number_out_of_stock_transactions from `Inventory Transaction Fact` ITF  where ITF.`Out of Stock Tag`='Yes'  %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_out_of_stock_parts=$row['number_out_of_stock_parts'];
		$number_out_of_stock_transactions=$row['number_out_of_stock_transactions'];

	}

	$sql=sprintf("select count(DISTINCT `Part SKU`) as parts, count(*) as number_transactions from `Inventory Transaction Fact`  where `Inventory Transaction Type`='Sale'   %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_parts=$row['parts'];
		$number_transactions=$row['number_transactions'];

	}
	$number_out_of_stock_parts=number($number_out_of_stock_parts).' ('.percentage($number_out_of_stock_parts,$number_parts).')';
	$number_out_of_stock_transactions=number($number_out_of_stock_transactions).' ('.percentage($number_out_of_stock_transactions,$number_transactions).')';

	$response=array('state'=>200,'number_out_of_stock_parts'=>$number_out_of_stock_parts,'number_out_of_stock_transactions'=>$number_out_of_stock_transactions);
	echo json_encode($response);

}



function out_of_stock_lost_revenue_data($data) {
	global $corporate_currency;
	$from=$data['from'];
	$to=$data['to'];

	$lost_revenue=0;
	$revenue=0;

	/*  Base calculation but we can do it also with the orders data

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Actual Shipping Date`');
	$sql=sprintf("select sum(`Order Out of Stock Lost Amount`) as lost_revenue,sum(`Order Transaction Amount`) as revenue from `Order Transaction Fact`  where `Current Dispatching State`='Dispatched'    %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$lost_revenue=$row['lost_revenue'];
	$revenue=$row['revenue'];
	}


*/
	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Dispatched Date`');
	$sql=sprintf("select sum(`Order Out of Stock Net Amount`*`Order Currency Exchange`) as lost_revenue , sum(`Order Items Net Amount`*`Order Currency Exchange`) as revenue from `Order Dimension`  where `Order Current Dispatch State`='Dispatched'    %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$lost_revenue=$row['lost_revenue'];
		$revenue=$row['revenue'];
	}




	$lost_revenue=money($lost_revenue,$corporate_currency).' ('.percentage($lost_revenue,$revenue).')';

	$response=array('state'=>200,'lost_revenue'=>$lost_revenue);
	echo json_encode($response);

}

function out_of_stock_customer_data($data) {

	$from=$data['from'];
	$to=$data['to'];

	$number_out_of_stock_customers=0;
	$number_customers=0;


	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Dispatched Date`');

	$sql=sprintf("select count(DISTINCT `Order Customer Key`) as number_out_of_stock_customers from `Order Dimension`  where `Order Current Dispatch State`='Dispatched' and  `Order with Out of Stock`='Yes'    %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_out_of_stock_customers=$row['number_out_of_stock_customers'];
	}
	//print "$sql\n";
	$sql=sprintf("select count(DISTINCT `Order Customer Key`) as customers from `Order Dimension`  where `Order Current Dispatch State`='Dispatched'     %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_customers=$row['customers'];
	}
	//print "$sql\n";
	$_number_out_of_stock_customers=number($number_out_of_stock_customers).' ('.percentage($number_out_of_stock_customers,$number_customers).')';

	$response=array('state'=>200,'number_out_of_stock_customers'=>$_number_out_of_stock_customers);
	echo json_encode($response);

}

function out_of_stock_order_data($data) {

	$from=$data['from'];
	$to=$data['to'];

	$number_out_of_stock_orders=0;
	$number_orders=0;

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Order Dispatched Date`');

	$sql=sprintf("select count(DISTINCT `Order Key`) as number_out_of_stock_orders from `Order Dimension`  where `Order Current Dispatch State`='Dispatched' and  `Order with Out of Stock`='Yes'    %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_out_of_stock_orders=$row['number_out_of_stock_orders'];
	}
	//print "$sql\n";
	$sql=sprintf("select count(DISTINCT `Order Key`) as orders from `Order Dimension`  where `Order Current Dispatch State`='Dispatched'     %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_orders=$row['orders'];
	}
	//print "$sql\n";
	$_number_out_of_stock_orders=number($number_out_of_stock_orders).' ('.percentage($number_out_of_stock_orders,$number_orders).')';

	$response=array('state'=>200,'number_out_of_stock_orders'=>$_number_out_of_stock_orders);
	echo json_encode($response);

}
function get_tax_categories_elements_chooser($data) {

	global $corporate_country_2alpha_code,$corporate_country_code;


	$from=$data['from'];
	$to=$data['to'];

	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];

	$elements_chooser_customers='';
	$elements_chooser_invoices='';

	//$regions_selected=$_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions'];
	
	
	
	//$regions_selected=$data['regions'];
	//print_r($_SESSION['state']['report_sales_with_no_tax'][$corporate_country_2alpha_code]['regions']);
	
	$regions_selected=json_decode(base64_decode($data['regions']),true);
	$tax_category_selected=json_decode(base64_decode($data['tax_category']),true);

	//print_r($regions_selected);
	//exit;


	$tax_categories=array();
	$sql=sprintf("select `Invoice Tax Code`,`Tax Category Key`,`Tax Category Name`,`Tax Category Code` from `Invoice Dimension` left join   `Tax Category Dimension`  on (`Tax Category Code`=`Invoice Tax Code`) where true $where_interval group by `Invoice Tax Code`",
		$where_interval
		
	);

	//print $sql;
	
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['Tax Category Code']=='UNK')
			$description='';
		else
			$description=': '.$row['Tax Category Name'];
		$tax_categories[$row['Tax Category Key']]=array(
			'code'=>$row['Tax Category Code'],
			'name'=>$description,
			'selected'=>$tax_category_selected[$row['Tax Category Code']]  
			);
	}

	$elements_tax_categories_customers_ids=array();
	$elements_tax_categories_invoices_ids=array();
	foreach ($tax_categories as $tax_category) {
		$elements_chooser_customers.='<span onClick="change_elements(this,\'tax_categories_customers\')" style="float:right;margin-left:12px" class="'.($tax_category['selected']?'selected':'').'" id="elements_tax_category_'.$tax_category['code'].'_customers">'.$tax_category['code'].$tax_category['name'].' (<span id="elements_tax_category_'.$tax_category['code'].'_customers_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span>';
		$elements_chooser_invoices.='<span onClick="change_elements(this,\'tax_categories_invoices\')" style="float:right;margin-left:12px" class="'.($tax_category['selected']?'selected':'').'" id="elements_tax_category_'.$tax_category['code'].'_invoices">'.$tax_category['code'].$tax_category['name'].' (<span id="elements_tax_category_'.$tax_category['code'].'_invoices_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span>';
		$elements_tax_categories_customers_ids[]='elements_tax_category_'.$tax_category['code'].'_customers';
		$elements_tax_categories_invoices_ids[]='elements_tax_category_'.$tax_category['code'].'_invoices';
	}
	$elements_chooser_customers.='<span style="float:right;margin-left:2px;margin-right:15px">]</span> ';
	$elements_chooser_invoices.='<span style="float:right;margin-left:2px;margin-right:15px">]</span> ';



	$elements_regions_customers_ids=array();
	$elements_regions_invoices_ids=array();
	switch ( $corporate_country_2alpha_code) {
	case('GB'):
	
		$elements_chooser_customers.='<span onClick="change_elements(this,\'regions_customers\')" style="float:right;margin-left:2px;" class="'.($regions_selected['GBIM']?'selected':'').'" id="elements_region_GBIM_customers">GB+IM</span> <span style="float:right;margin-left:2px" >|</span> <span  onClick="change_elements(this,\'regions_customers\')"  style="float:right;margin-left:2px" class="'.($regions_selected['EU']?'selected':'').'" id="elements_region_EU_customers">EU (no GB,IM)</span> <span style="float:right;margin-left:2px" >|</span> <span  onClick="change_elements(this,\'regions_customers\')"  style="float:right;margin-left:2px" class="'.($regions_selected['NOEU']?'selected':'').'" id="elements_region_NOEU_customers">No EU</span> ';
		$elements_chooser_invoices.='<span onClick="change_elements(this,\'regions_invoices\')" style="float:right;margin-left:2px;" class="'.($regions_selected['GBIM']?'selected':'').'" id="elements_region_GBIM_invoices">GB+IM</span> <span style="float:right;margin-left:2px" >|</span> <span onClick="change_elements(this,\'regions_invoices\')" style="float:right;margin-left:2px" class="'.($regions_selected['EU']?'selected':'').'" id="elements_region_EU_invoices">EU (no GB,IM)</span> <span style="float:right;margin-left:2px" >|</span> <span onClick="change_elements(this,\'regions_invoices\')" style="float:right;margin-left:2px" class="'.($regions_selected['NOEU']?'selected':'').'" id="elements_region_NOEU_invoices">No EU</span> ';
		$elements_regions_customers_ids=array('elements_region_GBIM_customers','elements_region_EU_customers','elements_region_NOEU_customers');
		$elements_regions_invoices_ids=array('elements_region_GBIM_invoices','elements_region_EU_invoices','elements_region_NOEU_invoices');

		break;
	case('ES'):
		$elements_chooser_customers.='<span style="float:right;margin-left:2px;" class="'.($regions_selected['ES']?'selected':'').'" id="elements_region_ES_customers" table_type="ES">ES</span> <span style="float:right;margin-left:2px" >|</span> <span style="float:right;margin-left:2px" class="'.($regions_selected['EU']?'selected':'').'" id="elements_region_EU_customers" table_type="EU">EU (no ES)</span> <span style="float:right;margin-left:2px" >|</span> <span style="float:right;margin-left:2px" class="'.($regions_selected['NOEU']?'selected':'').'" id="elements_region_NOEU_customers">No EU</span> ';
		$elements_chooser_invoices.='<span style="float:right;margin-left:2px;" class="'.($regions_selected['GBIM']?'selected':'').'" id="elements_region_GBIM_invoices">GB+IM</span> <span style="float:right;margin-left:2px" >|</span> <span style="float:right;margin-left:2px" class="'.($regions_selected['EU']?'selected':'').'" id="elements_region_EU_invoices">EU (no GB,IM)</span> <span style="float:right;margin-left:2px" >|</span> <span style="float:right;margin-left:2px" class="'.($regions_selected['NOEU']?'selected':'').'" id="elements_region_NOEU_invoices">No EU</span> ';
		$elements_regions_customers_ids=array('elements_region_ES_customers','elements_region_EU_customers','elements_region_NOEU_customers');
		$elements_regions_invoices_ids=array('elements_region_ES_invoices','elements_region_EU_invoices','elements_region_NOEU_invoices');

		break;
	}


	$elements_chooser_customers.='<span style="float:right;margin-left:0px" >[</span>';
	$elements_chooser_invoices.='<span style="float:right;margin-left:0px" >[</span>';

	$response= array(
		
		'state'=>200,
		'elements_chooser_customers'=>$elements_chooser_customers,
		'elements_chooser_invoices'=>$elements_chooser_invoices,
		'elements_tax_categories_customers_ids'=>$elements_tax_categories_customers_ids,
		'elements_tax_categories_invoices_ids'=>$elements_tax_categories_invoices_ids,
		'elements_regions_customers_ids'=>$elements_regions_customers_ids,
		'elements_regions_invoices_ids'=>$elements_regions_invoices_ids,
		'x'=>$_SESSION['state']['report_sales_with_no_tax']['period']

	);
	echo json_encode($response);


}

function get_tax_categories_numbers($data) {
	$user=$data['user'];
	$from=$data['from'];
	$to=$data['to'];
	$country=$data['country'];

	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if (count($user->stores)==0)return;

	$where=sprintf(' where  `Invoice Store Key` in (%s) ',join(',',$user->stores));


	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$_where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where_interval=$_where_interval['mysql'];
	$where.=$where_interval;






	if ($country=='GB') {
		$_country='"GB","IM"';
	}else {
		$_country='"'.$country.'"';

	}


	$elements_region=$_SESSION['state']['report_sales_with_no_tax'][$country]['regions'];



	$where_elements_region='';
	if (isset($elements_region['GBIM']) and $elements_region['GBIM']) {
		$where_elements_region.=' or `Invoice Billing Country 2 Alpha Code` in ('.$_country.')  ';
	}
	if ($elements_region['EU']) {
		$where_elements_region.=' or ( `Invoice Billing Country 2 Alpha Code` not in ('.$_country.') and `European Union`="Yes" ) ';
	}
	if ($elements_region['NOEU']) {
		$where_elements_region.=' or (`Invoice Billing Country 2 Alpha Code` not in ('.$_country.') and `European Union`="No")  ';
	}
	$where_elements_region=preg_replace('/^\s*or\s*/','',$where_elements_region);
	if ( $where_elements_region=='')
		$where_elements_region=' false ';
	$where.=" and ($where_elements_region) ";


$elements_numbers=array();

	$sql=sprintf("select `Invoice Tax Code`,`Tax Category Key`,`Tax Category Name`,`Tax Category Code` from `Invoice Dimension` left join   `Tax Category Dimension`  on (`Tax Category Code`=`Invoice Tax Code`) where true $where_interval group by `Invoice Tax Code`",
		prepare_mysql($from),
		prepare_mysql($to)
	);
//	print $sql;
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
	
	$elements_numbers['invoices'][$row['Invoice Tax Code']]=0;
		$elements_numbers['customers'][$row['Invoice Tax Code']]=0;
	
		}




	



	$sql="select count(distinct `Invoice Customer Key`) as customers,count(distinct `Invoice Key`)as invoices,`Invoice Tax Code`  from `Invoice Dimension` left join kbase.`Country Dimension` on (`Invoice Delivery Country 2 Alpha Code`=`Country 2 Alpha Code`)  left join `Tax Category Dimension` TC on (TC.`Tax Category Code`=`Invoice Tax Code`)  $where  group by  `Invoice Tax Code` ";

	//print $sql;
	$data=array();
	$res=mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$elements_numbers['invoices'][$row['Invoice Tax Code']]=number($row['invoices']);
		$elements_numbers['customers'][$row['Invoice Tax Code']]=number($row['customers']);

	}
	mysql_free_result($res);


	$response= array('state'=>200,'elements_numbers'=>$elements_numbers);
	echo json_encode($response);
}

function list_sales_per_store() {
	global $user,$corporate_currency;
	$conf=$_SESSION['state']['report_sales']['stores']['stores'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$percentages=false;

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state']['report_sales']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['report_sales']['to'];

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Invoice Date`');

	$_SESSION['state']['report_sales']['stores']['stores']['order']=$order;
	$_SESSION['state']['report_sales']['stores']['stores']['order_dir']=$order_dir;
	$_SESSION['state']['report_sales']['stores']['stores']['nr']=$number_results;
	$_SESSION['state']['report_sales']['stores']['stores']['sf']=$start_from;
	$_SESSION['state']['report_sales']['stores']['stores']['f_field']=$f_field;
	$_SESSION['state']['report_sales']['stores']['stores']['f_value']=$f_value;


	if (count($user->stores)==0) {
		$where=' where false ';
		$where_store=' where false ';
	}else {
		$where=' where true';
		$where.=sprintf(" and I.`Invoice Store Key` in (%s)",join(',',$user->stores));
		$where_store=sprintf(" where  `Store Key` in (%s)",join(',',$user->stores));
	}

	$filter_msg='';

	$wheref='';
	if ( $f_field=='name' and $f_value!='' )
		$wheref.=" and  `Store Name` like '%".addslashes( $f_value )."%'";
	elseif ( $f_field=='code'  and $f_value!='' )
		$wheref.=" and  `Store Code` like '".addslashes( $f_value )."%'";

	$sql="select count(*) as total from  `Store Dimension` $where_store   $wheref";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Store Dimension`  $where_store  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);
	}

	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;



	if ($order=='code')
		$order='`Store Code`';

	elseif ($order=='store_key')
		$order='`Store Key`';

	elseif ($order=='name')
		$order='`Store Name`';
	else
		$order='`Store Code`';







	$sum_total_invoices=0;
	$sum_total_total=0;

	$_adata=array();
	$sql="select `Store Key`,`Store Name`,`Store Code`,`Store Key`,`Store Currency Code` from `Store Dimension`  $where_store $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	$res = mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="store.php?view=sales&id=%d">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);
		$_adata[$row['Store Key']]=array(
			'code'=>$code,
			'store_key'=>$row['Store Key'],
			'name'=>$name,
			'invoices'=>0,
			'_invoices'=>0,
			'invoices_share'=>'',
			'first_date'=>'',
			'dc_sales'=>money(0,$corporate_currency),
			'_dc_sales'=>0,

			'sales'=>money(0,$row['Store Currency Code']),
			'dc_sales_share'=>'',
			'invoices_delta'=>'',
			'sales_delta'=>'',
			'dc_sales_delta'=>'',
		);
	}
	mysql_free_result($res);



	if ($to=='')
		$end=date("Y-m-d");
	else
		$end=$to;

	if ($from=='') {
		$sql=sprintf("select min(DATE(`Invoice Date`)) as from_date  from `Invoice Dimension` where `Invoice Store Key` in (%s) ",
			join(',',$user->stores)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$start=$row['from_date'];
		}
	}else {
		$start=$from;
	}

	$days_between = ceil(abs(strtotime($end) - strtotime($start)) / 86400);
	$last_year_sum_total_invoices=0;
	$last_year_sum_total_total=0;
	$last_year_adata=array();

	if ($days_between<=366) {
		$last_year_from=date("Y-m-d",strtotime($start.' -1 year'));
		$last_year_to=date("Y-m-d",strtotime($end.' -1 year'));
		$last_year_date_interval=prepare_mysql_dates($last_year_from.' 00:00:00',$last_year_to.' 23:59:59','`Invoice Date`');

		$sql="select `Invoice Currency`,`Invoice Store Key`,count(*) as invoices,sum(`Invoice Total Net Amount`) as total,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as dc_total from `Invoice Dimension` I  $where $wheref ".$last_year_date_interval['mysql']." group by I.`Invoice Store Key`    ";
		//print $sql;
		$res = mysql_query($sql);
		$last_year_adata=array();
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$last_year_sum_total_invoices+=$row['invoices'];

			$last_year_sum_total_total+=$row['dc_total'];
			// print_r($row);

			$last_year_adata[$row['Invoice Store Key']]['invoices']=number($row['invoices']);
			$last_year_adata[$row['Invoice Store Key']]['_invoices']=$row['invoices'];
			$last_year_adata[$row['Invoice Store Key']]['sales']=money($row['total'],$row['Invoice Currency']);
			$last_year_adata[$row['Invoice Store Key']]['_sales']=$row['total'];

			$last_year_adata[$row['Invoice Store Key']]['dc_sales']=money($row['dc_total'],$corporate_currency);
			$last_year_adata[$row['Invoice Store Key']]['_dc_sales']=$row['dc_total'];


			$_adata[$row['Invoice Store Key']]['invoices_delta']='<span title="'.number($row['invoices']).'">'.delta(0,$row['invoices']).'</span>';
			$_adata[$row['Invoice Store Key']]['sales_delta']='<span title="'.money($row['total'],$row['Invoice Currency']).'">'.delta(0,$row['total']).'</span>';
			$_adata[$row['Invoice Store Key']]['dc_sales_delta']='<span title="'.money($row['dc_total'],$corporate_currency).'">'.delta(0,$row['dc_total']).'</span>';


		}
		mysql_free_result($res);


	}






	$sql="select `Invoice Currency`,`Invoice Store Key`,count(*) as invoices,sum(`Invoice Total Net Amount`) as total ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as dc_total 	from `Invoice Dimension` I  left join `Store Dimension` on (`Invoice Store Key`=`Store Key`)  $where $wheref ".$date_interval['mysql']." group by I.`Invoice Store Key`    ";
	//print $sql;
	$res = mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$sum_total_invoices+=$row['invoices'];

		$sum_total_total+=$row['dc_total'];
		// print_r($row);

		$_adata[$row['Invoice Store Key']]['invoices']=number($row['invoices']);
		$_adata[$row['Invoice Store Key']]['_invoices']=$row['invoices'];
		$_adata[$row['Invoice Store Key']]['sales']=money($row['total'],$row['Invoice Currency']);
		$_adata[$row['Invoice Store Key']]['_sales']=$row['total'];

		$_adata[$row['Invoice Store Key']]['dc_sales']=money($row['dc_total'],$corporate_currency);
		$_adata[$row['Invoice Store Key']]['_dc_sales']=$row['dc_total'];

		if (array_key_exists($row['Invoice Store Key'],$last_year_adata)) {

			$_adata[$row['Invoice Store Key']]['invoices_delta']='<span title="'.$last_year_adata[$row['Invoice Store Key']]['invoices'].'">'.delta($row['invoices'],$last_year_adata[$row['Invoice Store Key']]['_invoices']).'</span>';
			$_adata[$row['Invoice Store Key']]['sales_delta']='<span title="'.$last_year_adata[$row['Invoice Store Key']]['sales'].'">'.delta($row['dc_total'],$last_year_adata[$row['Invoice Store Key']]['_sales']).'</span>';
			$_adata[$row['Invoice Store Key']]['dc_sales_delta']='<span title="'.$last_year_adata[$row['Invoice Store Key']]['dc_sales'].'">'.delta($row['dc_total'],$last_year_adata[$row['Invoice Store Key']]['_dc_sales']).'</span>';

		}else {
			$_adata[$row['Invoice Store Key']]['invoices_delta']=_('NA');
			$_adata[$row['Invoice Store Key']]['sales_delta']=_('NA');
			$_adata[$row['Invoice Store Key']]['dc_sales_delta']=_('NA');


		}

	}
	mysql_free_result($res);

	//print_r($_adata);
	foreach ($_adata as $key=>$value) {
		$value['invoices_share']=percentage($value['_invoices'],$sum_total_invoices);
		$value['dc_sales_share']=percentage($value['_dc_sales'],$sum_total_total);

		$adata[]=$value;

	}


	if ($total<=$number_results) {
		$adata[]=array(
			'store_key'=>'',
			'name'=>('Total'),
			'code'=>'',
			'invoices'=>number($sum_total_invoices),
			'dc_sales'=>money($sum_total_total,$corporate_currency),
			'dc_sales_share'=>'',
			'invoices_delta'=>'<span title="'.number($last_year_sum_total_invoices).'">'.delta($sum_total_invoices,$last_year_sum_total_invoices).'</span>',
			'sales_delta'=>'',
			'dc_sales_delta'=>'<span title="'.number($last_year_sum_total_total).'">'.delta($sum_total_total,$last_year_sum_total_total).'</span>'
		);
		$total_records++;
		$number_results++;
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_sales_per_invoice_category() {
	global $user,$corporate_currency;
	$conf=$_SESSION['state']['report_sales']['categories']['categories'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$percentages=false;

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state']['report_sales']['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state']['report_sales']['to'];

	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Invoice Date`');

	$_SESSION['state']['report_sales']['categories']['categories']['order']=$order;
	$_SESSION['state']['report_sales']['categories']['categories']['order_dir']=$order_dir;
	$_SESSION['state']['report_sales']['categories']['categories']['nr']=$number_results;
	$_SESSION['state']['report_sales']['categories']['categories']['sf']=$start_from;
	$_SESSION['state']['report_sales']['categories']['categories']['f_field']=$f_field;
	$_SESSION['state']['report_sales']['categories']['categories']['f_value']=$f_value;


	if (count($user->stores)==0) {
		$where=' where false ';
		$where_category=' where false ';
	}else {
		$where=' where true';
		$where.=sprintf(" and   `Category Subject`='Invoice' and `Category Branch Type`='Head'   and  I.`Invoice Store Key` in (%s)",join(',',$user->stores));
		$where_category=sprintf(" where `Category Subject`='Invoice' and `Category Branch Type`='Head'  and `Category Store Key` in (%s)",join(',',$user->stores));
	}

	$filter_msg='';

	$wheref='';
	if ( $f_field=='name' and $f_value!='' )
		$wheref.=" and  `Category Label` like '".addslashes( $f_value )."%'";
	elseif ( $f_field=='code'  and $f_value!='' )
		$wheref.=" and  `Category Code` like '".addslashes( $f_value )."%'";

	$sql="select count(*) as total from  `Category Dimension` C left join `Store Dimension` on  (`Category Store Key`=`Store Key`) $where_category   $wheref";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from ` `Category Dimension` C   $where_category  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);
	}

	$rtext=number($total_records)." ".ngettext('category','categories',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with code like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with label like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with code like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with label like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;



	if ($order=='code')
		$order='`Category Code`';

	elseif ($order=='category_key')
		$order='`Store Key`,C.`Category Function Order`';

	elseif ($order=='name')
		$order='`Category Label`';
	else
		$order='`Category Code`';







	$sum_total_invoices=0;
	$sum_total_total=0;

	$_adata=array();
	$sql="select C.`Category Key`,`Category Label`,`Category Code`,`Store Key`,`Store Currency Code`,`Store Code` from  `Category Dimension` C  left join `Store Dimension` on  (`Category Store Key`=`Store Key`)   $where_category $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	$res = mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$store=sprintf('<a href="store.php?view=sales&id=%d">%s</a>',$row['Store Key'],$row['Store Code']);


		$name=sprintf('<a href="report_sales_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Label']);
		$name=$row['Category Label'];
		$_adata[$row['Category Key']]=array(
			'store'=>$store,
			'category_key'=>$row['Category Key'],
			'name'=>$name,
			'invoices'=>0,
			'_invoices'=>0,
			'invoices_share'=>'',
			'first_date'=>'',
			'dc_sales'=>money(0,$corporate_currency),
			'_dc_sales'=>0,

			'sales'=>money(0,$row['Store Currency Code']),
			'dc_sales_share'=>'',
			'invoices_delta'=>'',
			'sales_delta'=>'',
			'dc_sales_delta'=>'',
		);
	}
	mysql_free_result($res);



	if ($to=='')
		$end=date("Y-m-d");
	else
		$end=$to;

	if ($from=='') {
		$sql=sprintf("select min(DATE(`Invoice Date`)) as from_date  from `Invoice Dimension` left join `Category Bridge` B on (`Invoice Key`=B.`Category Key`)   where   `Category Key`>0 and  `Invoice Store Key` in (%s) ",
			join(',',$user->stores)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$start=$row['from_date'];
		}
	}else {
		$start=$from;
	}

	$days_between = ceil(abs(strtotime($end) - strtotime($start)) / 86400);
	$last_year_sum_total_invoices=0;
	$last_year_sum_total_total=0;
	$last_year_adata=array();

	if ($days_between<=366) {
		$last_year_from=date("Y-m-d",strtotime($start.' -1 year'));
		$last_year_to=date("Y-m-d",strtotime($end.' -1 year'));
		$last_year_date_interval=prepare_mysql_dates($last_year_from.' 00:00:00',$last_year_to.' 23:59:59','`Invoice Date`');

		$sql="select `Invoice Currency`,B.`Category Key`,count(*) as invoices,sum(`Invoice Total Net Amount`) as total,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as dc_total from `Invoice Dimension` I  left join `Category Bridge` B on (`Invoice Key`=B.`Subject Key`) left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`)   $where $wheref ".$last_year_date_interval['mysql']." group by B.`Category Key`    ";

		$res = mysql_query($sql);
		$last_year_adata=array();
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$last_year_sum_total_invoices+=$row['invoices'];

			$last_year_sum_total_total+=$row['dc_total'];
			// print_r($row);

			$last_year_adata[$row['Category Key']]['invoices']=number($row['invoices']);
			$last_year_adata[$row['Category Key']]['_invoices']=$row['invoices'];
			$last_year_adata[$row['Category Key']]['sales']=money($row['total'],$row['Invoice Currency']);
			$last_year_adata[$row['Category Key']]['_sales']=$row['total'];

			$last_year_adata[$row['Category Key']]['dc_sales']=money($row['dc_total'],$corporate_currency);
			$last_year_adata[$row['Category Key']]['_dc_sales']=$row['dc_total'];


			$_adata[$row['Category Key']]['invoices_delta']='<span title="'.number($row['invoices']).'">'.delta(0,$row['invoices']).'</span>';
			$_adata[$row['Category Key']]['sales_delta']='<span title="'.money($row['total'],$row['Invoice Currency']).'">'.delta(0,$row['total']).'</span>';
			$_adata[$row['Category Key']]['dc_sales_delta']='<span title="'.money($row['dc_total'],$corporate_currency).'">'.delta(0,$row['dc_total']).'</span>';


		}
		mysql_free_result($res);


	}






	$sql="select `Invoice Currency`,B.`Category Key`,count(*) as invoices,sum(`Invoice Total Net Amount`) as total ,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) as dc_total from	`Invoice Dimension` I  left join `Category Bridge` B on (`Invoice Key`=B.`Subject Key`) left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`)  $where $wheref ".$date_interval['mysql']." group by B.`Category Key`    ";
	// print $sql;
	$res = mysql_query($sql);
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$sum_total_invoices+=$row['invoices'];

		$sum_total_total+=$row['dc_total'];
		// print_r($row);

		$_adata[$row['Category Key']]['invoices']=number($row['invoices']);
		$_adata[$row['Category Key']]['_invoices']=$row['invoices'];
		$_adata[$row['Category Key']]['sales']=money($row['total'],$row['Invoice Currency']);
		$_adata[$row['Category Key']]['_sales']=$row['total'];

		$_adata[$row['Category Key']]['dc_sales']=money($row['dc_total'],$corporate_currency);
		$_adata[$row['Category Key']]['_dc_sales']=$row['dc_total'];

		if (array_key_exists($row['Category Key'],$last_year_adata)) {

			$_adata[$row['Category Key']]['invoices_delta']='<span title="'.$last_year_adata[$row['Category Key']]['invoices'].'">'.delta($row['invoices'],$last_year_adata[$row['Category Key']]['_invoices']).'</span>';
			$_adata[$row['Category Key']]['sales_delta']='<span title="'.$last_year_adata[$row['Category Key']]['sales'].'">'.delta($row['dc_total'],$last_year_adata[$row['Category Key']]['_sales']).'</span>';
			$_adata[$row['Category Key']]['dc_sales_delta']='<span title="'.$last_year_adata[$row['Category Key']]['dc_sales'].'">'.delta($row['dc_total'],$last_year_adata[$row['Category Key']]['_dc_sales']).'</span>';

		}else {
			$_adata[$row['Category Key']]['invoices_delta']=_('NA');
			$_adata[$row['Category Key']]['sales_delta']=_('NA');
			$_adata[$row['Category Key']]['dc_sales_delta']=_('NA');


		}

	}
	mysql_free_result($res);

	//print_r($_adata);
	foreach ($_adata as $key=>$value) {
		$value['invoices_share']=percentage($value['_invoices'],$sum_total_invoices);
		$value['dc_sales_share']=percentage($value['_dc_sales'],$sum_total_total);

		$adata[]=$value;

	}


	if ($total<=$number_results) {
		$adata[]=array(
			'store_key'=>'',
			'name'=>'',
			'store'=>('Total'),
			'code'=>'',
			'invoices'=>number($sum_total_invoices),
			'dc_sales'=>money($sum_total_total,$corporate_currency),
			'dc_sales_share'=>'',
			'invoices_delta'=>'<span title="'.number($last_year_sum_total_invoices).'">'.delta($sum_total_invoices,$last_year_sum_total_invoices).'</span>',
			'sales_delta'=>'',
			'dc_sales_delta'=>'<span title="'.number($last_year_sum_total_total).'">'.delta($sum_total_total,$last_year_sum_total_total).'</span>'
		);
		$total_records++;
		$number_results++;
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}


function list_assets_sales_history() {

	global $corporate_currency,$user;
	if (isset( $_REQUEST['scope']))
		$scope=$_REQUEST['scope'];
	else {
		exit('x');
	}

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit('x2');
	}

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit('x3');
	}

	if ($scope=='assets') {
		$conf=$_SESSION['state'][$parent]['sales_history'];

	}elseif ($scope=='report_sales') {

		$conf=$_SESSION['state'][$scope][$parent]['sales_history'];

	}else {
		exit('x4');
	}


	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {
		$from=$_SESSION['state'][$parent]['from'];
	}
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$parent]['to'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['timeline_group']))
		$timeline_group=$_REQUEST['timeline_group'];
	else
		$timeline_group=$conf['timeline_group'];


	if ($scope=='assets') {
		$_SESSION['state'][$parent]['sales_history']['timeline_group']=$timeline_group;

		$_SESSION['state'][$parent]['sales_history']['order']=$order;
		$_SESSION['state'][$parent]['sales_history']['order_dir']=$order_direction;
		$_SESSION['state'][$parent]['sales_history']['nr']=$number_results;
		$_SESSION['state'][$parent]['sales_history']['sf']=$start_from;
		$_SESSION['state'][$parent]['sales_history']['f_field']=$f_field;
		$_SESSION['state'][$parent]['sales_history']['f_value']=$f_value;

		$_SESSION['state'][$parent]['from']=$from;
		$_SESSION['state'][$parent]['to']=$to;

	}elseif ($scope=='report_sales') {

		$_SESSION['state'][$scope][$parent]['sales_history']['timeline_group']=$timeline_group;

		$_SESSION['state'][$scope][$parent]['sales_history']['order']=$order;
		$_SESSION['state'][$scope][$parent]['sales_history']['order_dir']=$order_direction;
		$_SESSION['state'][$scope][$parent]['sales_history']['nr']=$number_results;
		$_SESSION['state'][$scope][$parent]['sales_history']['sf']=$start_from;
		$_SESSION['state'][$scope][$parent]['sales_history']['f_field']=$f_field;
		$_SESSION['state'][$scope][$parent]['sales_history']['f_value']=$f_value;

		$_SESSION['state'][$scope]['from']=$from;
		$_SESSION['state'][$scope]['to']=$to;

	}




	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';


	if (!$to)$to=date("Y-m-d");


	switch ($parent) {
	case('family'):
		$where=sprintf(" where  `Product Family Key`=%d  ",$parent_key);


		$sql=sprintf("select Date(`Product Family Valid From`) as date ,`Product Family Currency Code` from `Product Family Dimension`where  `Product Family Key`=%d  ",$parent_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}
			$currency=$row['Product Family Currency Code'];
		}
		//print "$sql z $from z";

		break;
	case('department'):


		$sql=sprintf("select Date(`Product Department Valid From`) as date ,`Product Department Currency Code` from `Product Department Dimension`where  `Product Department Key`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}
			$currency=$row['Product Department Currency Code'];
		}

		$where=sprintf(" where  `Product Department Key`=%d  ",$parent_key);
		break;
	case('store'):


		$sql=sprintf("select Date(`Store Valid From`) as date ,`Store Currency Code` from `Store Dimension`where  `Store Key`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}
			$currency=$row['Store Currency Code'];

		}

		$where=sprintf(" where  `Store Key`=%d  ",$parent_key);
		break;
	case('stores'):

		if (count($user->stores)>0) {

			$sql=sprintf("select min(Date(`Store Valid From`)) as date  from `Store Dimension`where  `Store Key` in (%s)  ",join(',',$user->stores));
			//print $sql;
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				if (!$from) {
					$from=$row['date'];
				}


			}
			$where=sprintf(" where  `Store Key` in (%s)  ",join(',',$user->stores));
		}else {
			$from='';
			$where=sprintf(" where false  ");
		}
		$currency=$corporate_currency;

		break;
	case('product'):

		$sql=sprintf("select Date(`Product Valid From`) as date ,`Product Currency` from `Product Dimension` where  `Product ID`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}
			$currency=$row['Product Currency'];
		}


		$where=sprintf(" where  `Product ID`=%d  ",$parent_key);
		break;

	}




	switch ($timeline_group) {
	case 'year':
		$group='  group by Year(`Date`) ';
		$groupi=' group by Year(`Invoice Date`) ';
		$anchori='Year(`Invoice Date`) as date';
		break;

	case 'month':
		$group=' group by DATE_FORMAT(`Date`,"%m%Y") ';
		$groupi=' group by DATE_FORMAT(`Invoice Date`,"%m%Y") ';
		$anchori='DATE_FORMAT(`Invoice Date`,"%m%Y") as date';
		break;
	case 'day':
		$group=' group by (`Date`) ';
		$groupi=' group by `Invoice Date` ';
		$anchori='Date(`Invoice Date`) as date';
		break;
	default:
		$group=' group by YEARWEEK(`Date`) ';
		$groupi=' group by YEARWEEK(`Invoice Date`) ';
		$anchori='YEARWEEK(`Invoice Date`) as date';
		break;
	}


	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Date`');
	$where_interval=$where_interval['mysql'];
	//$where.=$where_interval;
	$wheref='';


	// if ($f_field=='note' and $f_value!='')
	//  $wheref.=" and  `Product Note` like '%".addslashes($f_value)."%'";
	// elseif ($f_field=='author' and $f_value!='')
	//  $wheref.=" and  `User Alias` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from  kbase.`Date Dimension`  where true $where_interval $wheref $group";

	$res = mysql_query($sql);
	$total= mysql_num_rows($res);


	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from   kbase.`Date Dimension`   $where_interval $group";
		$result=mysql_query($sql);
		$total_records= mysql_num_rows($result);
		$filtered=$total_records-$total;
		mysql_free_result($result);


	}
	//print $total_records;
	switch ($timeline_group) {
	case 'year':
		$rtext=number($total_records)."  ".ngettext('year','years',$total_records);
		break;

	case 'month':
		$rtext=number($total_records)." ".ngettext('month','months',$total_records);
		break;
	case 'day':
		$rtext=number($total_records)." ".ngettext('day','days',$total_records);

		break;
	default:
		$rtext=number($total_records)." ".ngettext('week','weeks',$total_records);

		break;
	}


	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$sql="select  DATE_FORMAT(`Date`,'%m%Y') as month , Year(`Date`) as year, YEARWEEK(`Date`) as week,  `Date` from kbase.`Date Dimension`where true  $where_interval $group order by `Date` desc  limit $start_from,$number_results ";
	//print $sql;
	$result=mysql_query($sql);
	$ddata=array();

	$from_date='';
	$to_date='';
	//print $sql;
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$from_date=$data['Date'];
		//print $data['Date']."\n";

		switch ($timeline_group) {
		case 'year':
			$rtext=number($total_records)." ".ngettext('year','years',$total_records);
			$date=strftime("%Y", strtotime($data['Date']));
			$anchor=$data['year'];
			if ($to_date=='') {
				$to_date=$data['Date']+1;
			}
			break;

		case 'month':
			$rtext=number($total_records)." ".ngettext('month','months',$total_records);
			$date=strftime("%B %Y", strtotime($data['Date']));
			// $date=strftime("%a %d/%m/%Y", strtotime($data['Date']));
			if ($to_date=='')$to_date=$data['Date'];
			$anchor=$data['month'];

			break;
		case 'day':
			$rtext=number($total_records)." ".ngettext('day','days',$total_records);
			$date=strftime("%a %d/%m/%Y", strtotime($data['Date']));
			$anchor=$data['Date'];
			if ($to_date=='')$to_date=$data['Date'];
			break;
		default:
			$rtext=number($total_records)." ".ngettext('week','weeks',$total_records);
			$date=_('Week').' '.strftime("%V %Y", strtotime($data['Date']));
			$anchor=$data['week'];
			if ($to_date=='')$to_date=$data['Date'];
			break;
		}

		$ddata[$anchor]=array(
			'date'=>$date,
			'customers'=>0,
			'invoices'=>0,
			'sales'=>money(0,$currency),
		);

	}


	$from=$from_date.' 00:00:00';
	$to=$to_date.' 23:59:59';



	$where_interval=prepare_mysql_dates($from,$to,'`Invoice Date`');
	$where_interval=$where_interval['mysql'];

	$sql="select $anchori,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) net,sum(`Cost Supplier`+`Cost Storing`+`Cost Handing`+`Cost Shipping`-`Invoice Transaction Gross Amount`+`Invoice Transaction Total Discount Amount`) as profit, count(Distinct `Invoice Key`) as invoices,count(Distinct `Customer Key`) as customers from `Order Transaction Fact` $where $where_interval $groupi";
	//print $sql;
	$result=mysql_query($sql);

	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ddata[$data['date']]['invoices']=number($data['invoices']);
		$ddata[$data['date']]['customers']=number($data['customers']);
		$ddata[$data['date']]['sales']=money($data['net']);


	}


	$adata=array();
	foreach ($ddata as $key=>$value) {
		$adata[]=$value;
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results
		)
	);
	echo json_encode($response);

}




function list_inventory_assets_sales_history() {

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit();
	}

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit();
	}

	$conf=$_SESSION['state'][$parent]['sales_history'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {
		$from=$_SESSION['state'][$parent]['from'];
	}
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$parent]['to'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['timeline_group']))
		$timeline_group=$_REQUEST['timeline_group'];
	else
		$timeline_group=$conf['timeline_group'];



	$_SESSION['state'][$parent]['sales_history']['timeline_group']=$timeline_group;

	$_SESSION['state'][$parent]['sales_history']['order']=$order;
	$_SESSION['state'][$parent]['sales_history']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['sales_history']['nr']=$number_results;
	$_SESSION['state'][$parent]['sales_history']['sf']=$start_from;
	$_SESSION['state'][$parent]['sales_history']['f_field']=$f_field;
	$_SESSION['state'][$parent]['sales_history']['f_value']=$f_value;

	$_SESSION['state'][$parent]['from']=$from;
	$_SESSION['state'][$parent]['to']=$to;

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';


	if (!$to)$to=date("Y-m-d");
	global $corporate_currency;
	$currency=$corporate_currency;
	switch ($parent) {

	case('part'):

		$sql=sprintf("select Date(`Part Valid From`) as date  from `Part Dimension` where  `Part SKU`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}

		}

		$table='`Inventory Transaction Fact`';
		$where=sprintf(" where   `Part SKU`=%d  ",$parent_key);
		break;
		
		case('part_categories'):
		
		
		$sql=sprintf("select Date(min(`Part Valid From`)) as date  from `Part Dimension`  left join `Category Bridge` on (`Subject`='Part' and `Subject Key`=`Part SKU`) where  `Category Key`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}

		}
		

			$table='`Inventory Transaction Fact` left join `Category Bridge` on (`Subject`="Part" and `Subject Key`=`Part SKU`)';
		$where=sprintf(" where   `Category Key`=%d  ",$parent_key);
		break;
		
		
	case('supplier'):

		$sql=sprintf("select Date(`Supplier Valid From`) as date  from `Supplier Dimension` where  `Supplier Key`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}

		}

		$table='`Inventory Transaction Fact`';
		$where=sprintf(" where   `Supplier Key`=%d  ",$parent_key);
		break;
	case('supplier_product'):

		$sql=sprintf("select Date(`Supplier Product Valid From`) as date  from `Supplier Product Dimension` where  `Supplier Product ID`=%d  ",$parent_key);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if (!$from) {
				$from=$row['date'];
			}

		}

		$table='`Inventory Transaction Fact`';
		$where=sprintf(" where   `Supplier Product ID`=%d  ",$parent_key);
		break;
	case('supplier_categories'):


		$table='`Inventory Transaction Fact` left join `Category Bridge` on (`Subject`="Supplier" and `Subject Key`=`Supplier Key`)';
		$where=sprintf(" where   `Category Key`=%d  ",$parent_key);
		break;
	default:
		exit('x');
	}




	switch ($timeline_group) {
	case 'year':
		$group='  group by Year(`Date`) ';
		$groupi=' group by Year(`Date`) ';
		$anchori='Year(`Date`) as date';
		break;

	case 'month':
		$group=' group by DATE_FORMAT(`Date`,"%m%Y") ';
		$groupi=' group by DATE_FORMAT(`Date`,"%m%Y") ';
		$anchori='DATE_FORMAT(`Date`,"%m%Y") as date';
		break;
	case 'day':
		$group=' group by (`Date`) ';
		$groupi=' group by `Date` ';
		$anchori='Date(`Date`) as date';
		break;
	default:
		$group=' group by YEARWEEK(`Date`) ';
		$groupi=' group by YEARWEEK(`Date`) ';
		$anchori='YEARWEEK(`Date`) as date';
		break;
	}


	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Date`');
	$where_interval=$where_interval['mysql'];
	//$where.=$where_interval;
	$wheref='';

	// if ($f_field=='note' and $f_value!='')
	//  $wheref.=" and  `Product Note` like '%".addslashes($f_value)."%'";
	// elseif ($f_field=='author' and $f_value!='')
	//  $wheref.=" and  `User Alias` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from  kbase.`Date Dimension`  where true $where_interval $wheref $group";
	//print $sql;
	$res = mysql_query($sql);
	$total= mysql_num_rows($res);


	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from   kbase.`Date Dimension`   $where_interval $group";
		$result=mysql_query($sql);
		$total_records= mysql_num_rows($result);
		$filtered=$total_records-$total;
		mysql_free_result($result);


	}
	//print $total_records;
	switch ($timeline_group) {
	case 'year':
		$rtext=number($total_records)."  ".ngettext('year','years',$total_records);
		break;

	case 'month':
		$rtext=number($total_records)." ".ngettext('month','months',$total_records);
		break;
	case 'day':
		$rtext=number($total_records)." ".ngettext('day','days',$total_records);

		break;
	default:
		$rtext=number($total_records)." ".ngettext('week','weeks',$total_records);

		break;
	}


	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$sql="select  DATE_FORMAT(`Date`,'%m%Y') as month , Year(`Date`) as year, YEARWEEK(`Date`) as week,  `Date` from kbase.`Date Dimension`where true  $where_interval $group order by `Date` desc  limit $start_from,$number_results ";
	//print $sql;
	$result=mysql_query($sql);
	$ddata=array();

	$from_date='';
	$to_date='';

	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($to_date=='')$to_date=$data['Date'];
		$from_date=$data['Date'];
		//print $data['Date']."\n";

		switch ($timeline_group) {
		case 'year':
			$rtext=number($total_records)." ".ngettext('year','year',$total_records);
			$date=strftime("%Y", strtotime($data['Date']));
			$anchor=$data['year'];

			break;

		case 'month':
			$rtext=number($total_records)." ".ngettext('month','months',$total_records);
			$date=strftime("%B %Y", strtotime($data['Date']));
			// $date=strftime("%a %d/%m/%Y", strtotime($data['Date']));

			$anchor=$data['month'];

			break;
		case 'day':
			$rtext=number($total_records)." ".ngettext('day','days',$total_records);
			$date=strftime("%a %d/%m/%Y", strtotime($data['Date']));
			$anchor=$data['Date'];

			break;
		default:
			$rtext=number($total_records)." ".ngettext('week','weeks',$total_records);
			$date=_('Week').' '.strftime("%V %Y", strtotime($data['Date']));
			$anchor=$data['week'];
			break;
		}

		$ddata[$anchor]=array(
			'date'=>$date,
			//'customers'=>0,
			'qty'=>0,
			'sales'=>money(0,$currency),
			'cost_sales'=>money(0,$currency),
			'out_of_stock'=>0,
			'out_of_stock_amount'=>money(0,$currency)
		);

	}


	$from=$from_date.' 00:00:00';
	$to=$to_date.' 23:59:59';

	$where_interval=prepare_mysql_dates($from,$to,'`Date`');
	$where_interval=$where_interval['mysql'];

	$sql="select $anchori,sum(`Out of Stock Lost Amount`) as out_of_stock_amount ,sum(`Inventory Transaction Quantity`) as qty , sum(`Inventory Transaction Amount`) as cost_sales,sum(`Amount In`) as sales from $table $where $where_interval and  `Inventory Transaction Type`='Sale'  $groupi";
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ddata[$data['date']]['qty']=number(-1*$data['qty'],0);
		$ddata[$data['date']]['sales']=money($data['sales']);
		$ddata[$data['date']]['cost_sales']=money(-1*$data['cost_sales']);
		$ddata[$data['date']]['out_of_stock_amount']=money($data['out_of_stock_amount']);
		
		
		
		
		
	}
	$sql="select $anchori,sum(`Inventory Transaction Quantity`) as qty  from $table $where $where_interval and  `Out of Stock Tag`='Yes'  $groupi";
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ddata[$data['date']]['out_of_stock']=number(-1*$data['qty'],0);
	}

	$adata=array();
	foreach ($ddata as $key=>$value) {
		$adata[]=$value;
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results
		)
	);
	echo json_encode($response);

}



function list_picked_dns() {
	global $myconf,$output_type,$user;


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit('no parent_key');
	}

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit('no parent');
	}


	switch ($parent) {
	case 'employee':
		$conf=$_SESSION['state']['report_pp']['picked_dns'];
		$conf_tag='part';
		$conf2=$_SESSION['state']['report_pp'];

		break;
	default:
		exit();


	}






	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf2['from'];

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf2['to'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state'][$conf_tag]['dn']['order']=$order;
	$_SESSION['state'][$conf_tag]['dn']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_tag]['dn']['nr']=$number_results;
	$_SESSION['state'][$conf_tag]['dn']['sf']=$start_from;
	$_SESSION['state'][$conf_tag]['dn']['f_field']=$f_field;
	$_SESSION['state'][$conf_tag]['dn']['f_value']=$f_value;

	$_SESSION['state'][$conf_tag]['from']=$from;
	$_SESSION['state'][$conf_tag]['to']=$to;


$group='';
	$table='`Delivery Note Dimension` D';

	switch ($parent) {
	case'employee':

		if ($parent_key) {
			$where=sprintf(' where `Delivery Note Assigned Picker Key`=%d  ',$parent_key);
		}else {
			$where=' where `Delivery Note Assigned Picker Key` is NULL  ';
		}

		break;
	default:
		$where='where false ';
	}

if ($from)$from=$from.' 00:00:00';
if ($to)$to=$to.' 23:59:59';

$where_interval=prepare_mysql_dates($from,$to,'`Delivery Note Date`');
$where.=$where_interval['mysql'];


	$wheref='';

	$sql="select count(distinct D.`Delivery Note Key`) as total from $table  $where $wheref ";
	// print $sql ;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(distinct D.`Delivery Note Key`) as total from $table  $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);
	}
	$rtext=number($total_records)." ".ngettext('delivery note','delivery notes',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';
	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note with ID")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes starting with')." <b>$f_value</b>)";
		break;
	case('customer_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note with with customer")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with customer')." <b>".$f_value."*</b>)";
		break;
	case('country'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note from")." <b>".$find_data."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('delivery note','delivery notes',$total)." "._('to')." ".$find_data;
		break;





	}




	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date' or $order=='')
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


	$sql="select *  from $table  $where $wheref $group order by $order $order_direction ".($output_type=='ajax'?"limit $start_from,$number_results":'');
	// print $sql;

	$adata=array();

	$res = mysql_query($sql);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$order_id=sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID']);
		$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Delivery Note Customer Key'],$row['Delivery Note Customer Name']);


		$type=$row['Delivery Note Type'];

		switch ($row['Delivery Note Parcel Type']) {
		case('Pallet'):
			$parcel_type='P';
			break;
		case('Envelope'):
			$parcel_type='e';
			break;
		default:
			$parcel_type='b';

		}

		if ($row['Delivery Note Number Parcels']=='') {
			$parcels='?';
		}
		elseif ($row['Delivery Note Parcel Type']=='Pallet' and $row['Delivery Note Number Boxes']) {
			$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$row['Delivery Note Number Boxes'].' b)';
		}
		else {
			$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type;
		}


		//if ($row['Delivery Note State']=='Dispatched')
		// $date=strftime("%e %b %y", strtotime($row['Delivery Note Date'].' +0:00'));
		//else
		$date=strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Delivery Note Date Created'].' +0:00'));




		$adata[]=array(
			'id'=>$order_id
			,'customer'=>$customer
			,'date'=>$date
			,'type'=>$type
			,'state'=>$row['Delivery Note XHTML State']
			//,'orders'=>$row['Delivery Note XHTML Orders']
			//,'invoices'=>$row['Delivery Note XHTML Invoices']
			,'weight'=>number($row['Delivery Note Weight'],1,true).' Kg'
			,'parcels'=>$parcels


		);
	}


	mysql_free_result($res);

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);

}

function list_packed_dns() {
	global $myconf,$output_type,$user;


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit('no parent_key');
	}

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit('no parent');
	}


	switch ($parent) {
	case 'employee':
		$conf=$_SESSION['state']['report_pp']['packed_dns'];
		$conf_tag='part';
		$conf2=$_SESSION['state']['report_pp'];

		break;
	default:
		exit();


	}






	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf2['from'];

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf2['to'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state'][$conf_tag]['dn']['order']=$order;
	$_SESSION['state'][$conf_tag]['dn']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_tag]['dn']['nr']=$number_results;
	$_SESSION['state'][$conf_tag]['dn']['sf']=$start_from;
	$_SESSION['state'][$conf_tag]['dn']['f_field']=$f_field;
	$_SESSION['state'][$conf_tag]['dn']['f_value']=$f_value;

	$_SESSION['state'][$conf_tag]['from']=$from;
	$_SESSION['state'][$conf_tag]['to']=$to;


$group='';
	$table='`Delivery Note Dimension` D';

	switch ($parent) {
	case'employee':

		if ($parent_key) {
			$where=sprintf(' where `Delivery Note Assigned Packer Key`=%d  ',$parent_key);
		}else {
			$where=' where `Delivery Note Assigned Packer Key` is NULL  ';
		}

		break;
	default:
		$where='where false ';
	}


if ($from)$from=$from.' 00:00:00';
if ($to)$to=$to.' 23:59:59';

$where_interval=prepare_mysql_dates($from,$to,'`Delivery Note Date`');
$where.=$where_interval['mysql'];


	$wheref='';

	$sql="select count(distinct D.`Delivery Note Key`) as total from $table  $where $wheref ";
	// print $sql ;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);
	if ($where=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(distinct D.`Delivery Note Key`) as total from $table  $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);
	}
	$rtext=number($total_records)." ".ngettext('delivery note','delivery notes',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';
	$filter_msg='';

	switch ($f_field) {
	case('public_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note with ID")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes starting with')." <b>$f_value</b>)";
		break;
	case('customer_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note with with customer")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('delivery notes with customer')." <b>".$f_value."*</b>)";
		break;
	case('country'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No delivery note from")." <b>".$find_data."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('delivery note','delivery notes',$total)." "._('to')." ".$find_data;
		break;





	}




	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date' or $order=='')
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


	$sql="select *  from $table  $where $wheref $group order by $order $order_direction ".($output_type=='ajax'?"limit $start_from,$number_results":'');
	// print $sql;

	$adata=array();

	$res = mysql_query($sql);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$order_id=sprintf('<a href="dn.php?id=%d">%s</a>',$row['Delivery Note Key'],$row['Delivery Note ID']);
		$customer=sprintf('<a href="customer.php?id=%d">%s</a>',$row['Delivery Note Customer Key'],$row['Delivery Note Customer Name']);


		$type=$row['Delivery Note Type'];

		switch ($row['Delivery Note Parcel Type']) {
		case('Pallet'):
			$parcel_type='P';
			break;
		case('Envelope'):
			$parcel_type='e';
			break;
		default:
			$parcel_type='b';

		}

		if ($row['Delivery Note Number Parcels']=='') {
			$parcels='?';
		}
		elseif ($row['Delivery Note Parcel Type']=='Pallet' and $row['Delivery Note Number Boxes']) {
			$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type.' ('.$row['Delivery Note Number Boxes'].' b)';
		}
		else {
			$parcels=number($row['Delivery Note Number Parcels']).' '.$parcel_type;
		}


		//if ($row['Delivery Note State']=='Dispatched')
		// $date=strftime("%e %b %y", strtotime($row['Delivery Note Date'].' +0:00'));
		//else
		$date=strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Delivery Note Date Created'].' +0:00'));




		$adata[]=array(
			'id'=>$order_id
			,'customer'=>$customer
			,'date'=>$date
			,'type'=>$type
			,'state'=>$row['Delivery Note XHTML State']
			//,'orders'=>$row['Delivery Note XHTML Orders']
			//,'invoices'=>$row['Delivery Note XHTML Invoices']
			,'weight'=>number($row['Delivery Note Weight'],1,true).' Kg'
			,'parcels'=>$parcels


		);
	}


	mysql_free_result($res);

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);

}

?>
