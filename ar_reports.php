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



case('out_of_stock_customer_data'):
$data=prepare_values($_REQUEST,array(
			'from'=>array('type'=>'date'),
			'to'=>array('type'=>'date')
		));

	out_of_stock_customer_data($data);
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
	transactions_parts_marked_as_out_of_stock();
	break;
case('parts_marked_as_out_of_stock'):
	parts_marked_as_out_of_stock();
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
case('customers'):
	$results=list_customers();
	break;
case('products'):
	$results=list_products();
	break;
case('ES_1'):
	es_1();
	break;


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
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state']['report_pp']['pickers']['order']=$order;
	$_SESSION['state']['report_pp']['pickers']['order_dir']=$order_direction;
	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($conf['from'],$conf['to']);
	} else {
		$_SESSION['state']['report_pp']['pickers']['from']=$date_interval['from'];
		$_SESSION['state']['report_pp']['pickers']['to']=$date_interval['to'];
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




	//select sum(`Product Gross Weight`*`Delivery Note Quantity`)as weight , count(distinct `Order Key`) as orders,count(distinct `Order Key`,OTF.`Product Key`) as units ,`Staff ID`,`Staff Alias` from `Staff Dimension` S left join `Company Position Staff Bridge` B on (B.`Staff Key`=S.`Staff Key`) left join `Company Position Dimension` P on (P.`Company Position Key`=B.`Position Key`) left join `Order Transaction Fact` OTF on (`Picker Key`=S.`Staff Key`) left join `Product Dimension` PD on (OTF.`Product ID`=PD.`Product ID`) where `Current Dispatching State` in ('Ready to Ship','Dispatched') and `Order Date`>='2012-01-01' and `Order Date`<='2012-12-31' group by `Picker Key` order by `Staff Alias`

	$sql=sprintf("select sum(`Product Gross Weight`*`Delivery Note Quantity`)as weight , count(distinct `Order Key`) as orders,count(distinct `Order Key`,OTF.`Product ID`) as units ,`Staff ID`,`Staff Alias` from `Staff Dimension` S left join `Company Position Staff Bridge` B on (B.`Staff Key`=S.`Staff Key`) left join `Company Position Dimension` P on (P.`Company Position Key`=B.`Position Key`) left join `Order Transaction Fact` OTF on (`Picker Key`=S.`Staff Key`) left join `Product Dimension` PD on (OTF.`Product ID`=PD.`Product ID`) where `Current Dispatching State` in ('Ready to Ship','Dispatched') %s group by `Picker Key` order by %s %s  ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));



	$sql=sprintf("select `Staff Name`,`Picker Key`,sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units from `Inventory Transaction Fact` left join `Staff Dimension` S on  (`Picker Key`=S.`Staff Key`)   where `Inventory Transaction Type`='Sale' %s group by `Picker Key` order by %s %s  ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));


	//$sql=sprintf("select sum(`Product Gross Weight`*`Delivery Note Quantity`)as weight , count(distinct `Order Key`) as orders,count(distinct `Order Key`,OTF.`Product ID`) as units from  `Order Transaction Fact` OTF left join `Product Dimension` PD on (OTF.`Product ID`=PD.`Product ID`) where `Current Dispatching State` in ('Ready to Ship','Dispatched') %s group by `Picker Key` order by %s %s  ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));


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
		$data[]=array(
			//  'tipo'=>($row['position_id']==2?_('FT'):''),
			'alias'=>$row['Staff Name'],
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
	elseif ($total<$number_results)
		$rtext=$total.' '.ngettext('record returned','records returned',$total);
	else
		$rtext='';
	$rtext_rpp='';

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
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_SESSION['state']['report_pp']['packers']['order']=$order;
	$_SESSION['state']['report_pp']['packers']['order_dir']=$order_direction;
	$date_interval=prepare_mysql_dates($from,$to,'`Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($conf['from'],$conf['to']);
	} else {
		$_SESSION['state']['report_pp']['packers']['from']=$date_interval['from'];
		$_SESSION['state']['report_pp']['packers']['to']=$date_interval['to'];
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
		$order='`Staff Alias`';

	$sql=sprintf("select sum(`Product Gross Weight`*`Delivery Note Quantity`)as weight , count(distinct `Order Key`) as orders,count(distinct `Order Key`,OTF.`Product Key`) as units ,`Staff ID`,`Staff Alias` from `Staff Dimension` S left join `Company Position Staff Bridge` B on (B.`Staff Key`=S.`Staff Key`) left join `Company Position Dimension` P on (P.`Company Position Key`=B.`Position Key`) left join `Order Transaction Fact` OTF on (`Packer Key`=S.`Staff Key`)  left join `Product Dimension` PD on (OTF.`Product ID`=PD.`Product ID`) where `Current Dispatching State` in ('Ready to Ship','Dispatched') %s   ",$date_interval['mysql']);
	$sql=sprintf("select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from `Inventory Transaction Fact`  where `Inventory Transaction Type`='Sale'  %s   ",$date_interval['mysql']);
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total_delivery_notes=$row['delivery_notes'];
		$total_units=$row['units'];
		$total_weight=$row['weight'];
	}
	//print $sql;
	$sql=sprintf("select sum(`Product Gross Weight`*`Delivery Note Quantity`)as weight , count(distinct `Order Key`) as orders,count(distinct `Order Key`,OTF.`Product Key`) as units ,`Staff ID`,`Staff Alias` from `Staff Dimension` S left join `Company Position Staff Bridge` B on (B.`Staff Key`=S.`Staff Key`) left join `Company Position Dimension` P on (P.`Company Position Key`=B.`Position Key`) left join `Order Transaction Fact` OTF on (`Packer Key`=S.`Staff Key`) left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PH.`Product ID`) where `Current Dispatching State` in ('Ready to Ship','Dispatched') %s group by `Packer Key` order by %s %s  ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));
	$sql=sprintf("select `Staff Alias`,`Packer Key`,sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units from `Inventory Transaction Fact` left join `Staff Dimension` S on  (`Packer Key`=S.`Staff Key`)   where `Inventory Transaction Type`='Sale' %s group by `Packer Key` order by %s %s  ",$date_interval['mysql'],addslashes($order),addslashes($order_direction));

	//  print $sql;
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
		$data[]=array(
			//  'tipo'=>($row['position_id']==2?_('FT'):''),
			'alias'=>$row['Staff Alias'],
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
	elseif ($total<$number_results)
		$rtext=$total.' '.ngettext('record returned','records returned',$total);
	else
		$rtext='';
	$rtext_rpp='';
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
	global $myconf;

	$conf=$_SESSION['state']['customers']['table'];
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
	$_SESSION['state']['customers']['table']['order']=$order;
	$_SESSION['state']['customers']['table']['order_dir']=$order_direction;
	$_SESSION['state']['customers']['table']['nr']=$number_results;
	$_SESSION['state']['customers']['table']['sf']=$start_from;
	$_SESSION['state']['customers']['table']['where']=$where;
	$_SESSION['state']['customers']['table']['f_field']=$f_field;
	$_SESSION['state']['customers']['table']['f_value']=$f_value;
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

	$sql="select  GROUP_CONCAT(`Invoice Key`) as invoice_keys,sum(`Invoice Total Tax Adjust Amount`) as adjust_tax,`Customer Main Location`,`Customer Key`,`Customer Name`,`Customer Main XHTML Email`,count(DISTINCT `Invoice Key`) as invoices,sum(`Invoice Total Amount`) as total, sum(`Invoice Total Net Amount`) as net from  `Invoice Dimension` I left join  `Customer Dimension` C  on (I.`Invoice Customer Key`=C.`Customer Key`)  $where $wheref  group by `Customer Key` order by total desc";
	//   print $sql;
	$adata=array();


	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		if ($data['total']<$umbral)
			break;
		$total++;

		$tax1=0;
		$tax2=0;

		$sql2=sprintf("select `Tax Code`,sum(`Tax Amount`) as amount from `Invoice Tax Bridge` where `Invoice Key` in (%s) group by `Tax Code`  ", $data['invoice_keys']);
		$res2=mysql_query($sql2);
		//print "$sql2<br>";
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
			'total'=>money($data['total']),
			'net'=>money($data['net']),
			'tax1'=>money($tax1),
			'tax2'=>money($tax2),
			'invoices'=>number($data['invoices']),
			'location'=>$data['Customer Main Location']


		);
	}
	mysql_free_result($result);

	$rtext=number($total).' '._('Records found');


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
function list_customers() {


	global $myconf,$output_type,$user;

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




	/*   if(isset( $_REQUEST['f_field'])) */
	/*     $f_field=$_REQUEST['f_field']; */
	/*   else */
	/*     $f_field=$conf['f_field']; */

	/*   if(isset( $_REQUEST['f_value'])) */
	/*      $f_value=$_REQUEST['f_value']; */
	/*    else */
	/*      $f_value=$conf['f_value']; */



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
	$rtext='';
	$rtext_rpp='';
	$total=$number_results;



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='invoices')
		$order='`Invoices`';

	else
		$order='`Balance`';


	$sql="select  `Store Code`,`Customer Type by Activity`,`Customer Last Order Date`,`Customer Main XHTML Telephone`,`Customer Key`,`Customer Name`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main Town`,`Customer Main Country First Division`,`Customer Main Delivery Address Postal Code`,`Customer Orders Invoiced` as Invoices , `Customer Net Balance` as Balance  from `Customer Dimension` C  left join `Store Dimension` SD on (C.`Customer Store Key`=SD.`Store Key`)  left join `Invoice Dimension` I on (`Invoice Customer Key`=`Customer Key`)  $where $wheref  group by `Customer Key` order by $order $order_direction limit $start_from,$number_results";

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
			'net_balance'=>money($data['Balance']),
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
	if ($output_type=='ajax') {
		echo json_encode($response);
		return;
	} else {
		return $response;
	}

}

function parts_marked_as_out_of_stock() {


	global $myconf,$output_type,$user;

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
	$order_direction='desc';
	$order_dir='desc';

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

	if (isset( $_REQUEST['store_keys'])    ) {
		$store=$_REQUEST['store_keys'];
		$_SESSION['state']['report_part_out_of_stock']['store_keys']=$store;
	} else
		$store=$_SESSION['state']['report_part_out_of_stock']['store_keys'];

	if ($store=='all') {
		$store=join(',',$user->stores);

	}


	$filter_msg='';
	$wheref='';
	// $int=prepare_mysql_dates($from,$to,'`Invoice Date`','only dates');
	$int=prepare_mysql_dates($from,$to,'`Date Picked`','only dates');
	//print"$from --> $to ";
	// print_r($int);

	$where='where `Inventory Transaction Type`="Sale"  and  `Out of Stock`>0  ';

	if ($int['mysql']!='') {
		$where.=sprintf('  %s ',$int['mysql']);

	}


	if (is_numeric($store)) {
		$where.=sprintf(' and `Store Key`=%d ',$store);
	}
	elseif ($store=='') {

		$where.=sprintf(' and false ',$store);

	}
	else {

		$where.=sprintf(' and `Store Key` in (%s) ',$store);

	}







	$wheref='';
	if ($f_field=='sku' and $f_value!='')
		$wheref.=" and  `Part SKU` like '".addslashes($f_value)."%'";
	elseif ($f_field=='used_in' and $f_value!='')
		$wheref.=" and  `Part Currently Used In` like '%".addslashes($f_value)."%'";

	$sql="select count(DISTINCT ITF.`Part SKU`) as total   from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`)  $where $wheref ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(DISTINCT ITF.`Part SKU`) astotal_without_filters  from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`)  $where ";
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


	$rtext=$total_records." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';






	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date')
		$order='`Date Picked`';
	elseif ($order=='reporter')
		$order='`Staff Alias`';
	else
		$order='`Date Picked`';


	$sql="select count(DISTINCT `Customer Key`) as Customers,count(DISTINCT `Order Key`) as Orders,ITF.`Part SKU`,`Part XHTML Currently Used In`,MAX(`Date Picked`) as `Date Picked` from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) left join `Staff Dimension` SD on (SD.`Staff Key`=ITF.`Picker Key`)  $where $wheref  group by ITF.`Part SKU` order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	// print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$adata[]=array(

			'sku'=>sprintf("<a href='report_out_of_stock_part.php?sku=%d'>SKU%05d</a>",$data['Part SKU'],$data['Part SKU']),
			'used_in'=>$data['Part XHTML Currently Used In'],
			'date'=>strftime("%a %e %b %y %H:%M", strtotime($data['Date Picked']." +00:00")),
			'orders'=>number($data['Orders']),
			'customers'=>number($data['Customers'])

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

function transactions_parts_marked_as_out_of_stock() {


	global $myconf,$output_type,$user;

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
	$order_direction='desc';
	$order_dir='desc';



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

	if (isset( $_REQUEST['store_keys'])    ) {
		$store=$_REQUEST['store_keys'];
		$_SESSION['state']['report_part_out_of_stock']['store_keys']=$store;
	} else
		$store=$_SESSION['state']['report_part_out_of_stock']['store_keys'];

	if ($store=='all') {
		$store=join(',',$user->stores);

	}


	$filter_msg='';
	$wheref='';
	// $int=prepare_mysql_dates($from,$to,'`Invoice Date`','only dates');
	$int=prepare_mysql_dates($from,$to,'`Date Picked`','only dates');





	//print"$from --> $to ";
	// print_r($int);

	$where='where `Inventory Transaction Type`="Sale"  and  `Out of Stock`>0  ';

	if ($int['mysql']!='') {
		$where.=sprintf('  %s ',$int['mysql']);

	}


	if (is_numeric($store)) {
		$where.=sprintf(' and `Store Key`=%d ',$store);
	}
	elseif ($store=='') {

		$where.=sprintf(' and false ',$store);

	}
	else {

		$where.=sprintf(' and `Store Key` in (%s) ',$store);

	}







	$wheref='';
	if ($f_field=='sku' and $f_value!='')
		$wheref.=" and  `Part SKU` like '".addslashes($f_value)."%'";
	elseif ($f_field=='used_in' and $f_value!='')
		$wheref.=" and  `Part Currently Used In` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total   from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`)  $where $wheref";
	// print "$sql";
	// exit;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters  from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`)  $where ";
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


	$rtext=$total_records." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
			break;
		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('sku'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
			break;
		case('used_in'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';






	$_order=$order;
	$_dir=$order_direction;


	if ($order=='date')
		$order='`Date Picked`';
	elseif ($order=='reporter')
		$order='`Staff Alias`';
	else
		$order='`Date Picked`';


	$sql="select `Note`,SD.`Staff Alias`,ITF.`Part SKU`,`Part XHTML Currently Used In`,`Date Picked`,ITF.`Picker Key` from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  left join `Order Transaction Fact` I on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) left join `Staff Dimension` SD on (SD.`Staff Key`=ITF.`Picker Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results";

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

			'sku'=>sprintf("<a href='report_out_of_stock_part.php?sku=%d'>SKU%05d</a>",$data['Part SKU'],$data['Part SKU']),
			'used_in'=>$data['Part XHTML Currently Used In'],
			'date'=>strftime("%a %e %b %y %H:%M", strtotime($data['Date Picked']." +00:00")),
			'reporter'=>$reporter,
			'note'=>$data['Note']

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

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {

		$from=$conf['from'];

	}

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else {

		$to=$conf['to'];

	}

	if (isset( $_REQUEST['currency_type'])) {
		$currency_type=$_REQUEST['currency_type'];
		$_SESSION['state']['report_sales_with_no_tax']['currency_type']=$currency_type;
	} else {

		$currency_type=$_SESSION['state']['report_sales_with_no_tax']['currency_type'];

	}



	$country=$corporate_country_2alpha_code;


	$elements_region=$_SESSION['state']['report_sales_with_no_tax'][$country]['regions'];
	if (isset( $_REQUEST['elements_region_GBIM'])) {
		$elements_region['GBIM']=$_REQUEST['elements_region_GBIM'];
	}
	if (isset( $_REQUEST['elements_region_EU'])) {
		$elements_region['EU']=$_REQUEST['elements_region_EU'];
	}
	if (isset( $_REQUEST['elements_region_NOEU'])) {
		$elements_region['NOEU']=$_REQUEST['elements_region_NOEU'];
	}
	$_SESSION['state']['report_sales_with_no_tax'][$country]['regions']=$elements_region;




	$elements_tax_category=$_SESSION['state']['report_sales_with_no_tax'][$country]['tax_category'];

	foreach ($elements_tax_category as $key=>$value) {
		if (isset( $_REQUEST['elements_tax_category_'.$key])) {
			$elements_tax_category[$key]=$_REQUEST['elements_tax_category_'.$key];
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
		$date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
	} else {
		$_SESSION['state']['report_sales_with_no_tax']['invoices']['from']=$date_interval['from'];
		$_SESSION['state']['report_sales_with_no_tax']['invoices']['to']=$date_interval['to'];
	}




	$where=sprintf(' where  `Invoice Store Key` in (%s) ',$stores);



	//$where.=$date_interval['mysql'];


	$where.=sprintf(" and   `Invoice Date`>=%s and   `Invoice Date`<=%s   "
		,prepare_mysql($date_interval['from'].' 00:00:00')
		,prepare_mysql($date_interval['to'].' 23:59:59')
	);


	//$where.=

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
	$rtext=$total_records." ".ngettext('invoice','invoices',$total_records);
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
						$sql=sprintf("select `HQ Currency` from `HQ Dimension` ");
					$res=mysql_query($sql);
				if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
					$corporate_currency=$row['HQ Currency'];
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

		$send_to=sprintf('<span style="font-family:courier">%s</span> <img src="art/flags/%s.gif" alt="(%s)" title="%s" />',$row['Country Code'],strtolower($row['Invoice Delivery Country 2 Alpha Code']),$row['Country Code'],$row['Country Name']);
		if ($row['European Union']=='Yes') {
			$send_to.=' <img src="art/flags/eu.gif" title="'._('European Union Member').'" >';
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

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {

		$from=$conf['from'];

	}

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else {

		$to=$conf['to'];

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
		$date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
	} else {
		$_SESSION['state']['report_sales_with_no_tax']['overview']['from']=$date_interval['from'];
		$_SESSION['state']['report_sales_with_no_tax']['overview']['to']=$date_interval['to'];
	}


	$where=sprintf(" where   `Invoice Date`>=%s and   `Invoice Date`<=%s   "
		,prepare_mysql($date_interval['from'].' 00:00:00')
		,prepare_mysql($date_interval['to'].' 23:59:59')
	);

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
		$where_extra=' and `Invoice Billing Country 2 Alpha Code` in ("GB","IM")';
	}else {
		$where_extra=sprintf(' and `Invoice Billing Country 2 Alpha Code`=%s',prepare_mysql($country));
		$country_label=$country;
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
			'invoices'=>number($row['invoices'])
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
			'invoices'=>number($row['invoices'])
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
			'invoices'=>number($row['invoices'])
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
	if (isset( $_REQUEST['where']))
		$where=stripslashes($_REQUEST['where']);
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else {

		$from=$conf['from'];

	}

	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else {

		$to=$conf['to'];

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


		if (isset( $_REQUEST['elements_region_'.$element_region])) {
			$elements_region[$element_region]=$_REQUEST['elements_region_'.$element_region];
		}

	}


	$_SESSION['state']['report_sales_with_no_tax'][$country]['regions']=$elements_region;




	$elements_tax_category=$_SESSION['state']['report_sales_with_no_tax'][$country]['tax_category'];

	foreach ($elements_tax_category as $key=>$value) {
		if (isset( $_REQUEST['elements_tax_category_'.$key])) {
			$elements_tax_category[$key]=$_REQUEST['elements_tax_category_'.$key];
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




	//    $_SESSION['state']['report_sales_with_no_tax']['customers']=array('f_show'=>$_SESSION['state']['report_sales_with_no_tax']['customers']['f_show']   ,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);


	$_SESSION['state']['report_sales_with_no_tax']['customers']['order']=$order;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['order_dir']=$order_direction;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['nr']=$number_results;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['sf']=$start_from;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['where']=$where;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['f_field']=$f_field;
	$_SESSION['state']['report_sales_with_no_tax']['customers']['f_value']=$f_value;


	$date_interval=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($_SESSION['state']['orders']['from'],$_SESSION['state']['orders']['to']);
	} else {
		$_SESSION['state']['report_sales_with_no_tax']['customers']['from']=$date_interval['from'];
		$_SESSION['state']['report_sales_with_no_tax']['customers']['to']=$date_interval['to'];
	}




	$where=sprintf(' where  `Invoice Store Key` in (%s) ',$stores);
	// $where.=$date_interval['mysql'];

	$where.=sprintf(" and   `Invoice Date`>=%s and   `Invoice Date`<=%s   "
		,prepare_mysql($date_interval['from'].' 00:00:00')
		,prepare_mysql($date_interval['to'].' 23:59:59')
	);

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



	$where_elements_region='';

	if ($country=='GB') {
		if ($elements_region['GBIM']) {
			$where_elements_region.=' or `Invoice Billing Country 2 Alpha Code` in ("GB","IM")  ';
		}
		if ($elements_region['EU']) {
			$where_elements_region.=' or ( `Invoice Billing Country 2 Alpha Code` not in ("GB","IM") and `European Union`="Yes" ) ';
		}
		if ($elements_region['NOEU']) {
			$where_elements_region.=' or (`Invoice Billing Country 2 Alpha Code` not in ("GB","IM") and `European Union`="No")  ';
		}
	}else {

		if ($elements_region[$country]) {
			$where_elements_region.=sprintf(' or `Invoice Billing Country 2 Alpha Code` =%s  ',prepare_mysql($country));
		}
		if ($elements_region['EU']) {
			$where_elements_region.=sprintf(' or ( `Invoice Billing Country 2 Alpha Code`!=%s and `European Union`="Yes" ) ',prepare_mysql($country));
		}
		if ($elements_region['NOEU']) {
			$where_elements_region.=sprintf(' or (`Invoice Billing Country 2 Alpha Code`!=%s and `European Union`="No")  ',prepare_mysql($country));
		}

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
	$rtext=$total_records." ".ngettext('customer','customers',$total_records);
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





			$send_to=sprintf('<span style="font-family:courier">%s</span> <img src="art/flags/%s.gif" alt="(%s)" title="%s" />',$row['Country Code'],strtolower($row['Invoice Delivery Country 2 Alpha Code']),$row['Country Code'],$row['Country Name']);
			if ($row['European Union']=='Yes') {
				$send_to.=' <img src="art/flags/eu.gif" title="'._('European Union Member').'" >';
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


	$rtext=$total_records." ".ngettext('Country','Countries',$total_records);
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


	$rtext=$total_records." ".ngettext('Region','Regions',$total_records);
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


	$rtext=$total_records." ".ngettext('Continent','Continents',$total_records);
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

function list_stores() {
	global $user;
	$conf=$_SESSION['state']['stores']['stores'];

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
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


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


	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
		$_SESSION['state']['stores']['stores']['percentages']=$percentages;
	} else
		$percentages=$_SESSION['state']['stores']['stores']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['stores']['stores']['period']=$period;
	} else
		$period=$_SESSION['state']['stores']['stores']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
		$_SESSION['state']['stores']['stores']['avg']=$avg;
	} else
		$avg=$_SESSION['state']['stores']['stores']['avg'];


	$_SESSION['state']['stores']['stores']['exchange_type']=$exchange_type;
	$_SESSION['state']['stores']['stores']['exchange_value']=$exchange_value;
	$_SESSION['state']['stores']['stores']['show_default_currency']=$show_default_currency;
	$_SESSION['state']['stores']['stores']['order']=$order;
	$_SESSION['state']['stores']['stores']['order_dir']=$order_dir;
	$_SESSION['state']['stores']['stores']['nr']=$number_results;
	$_SESSION['state']['stores']['stores']['sf']=$start_from;
	$_SESSION['state']['stores']['stores']['where']=$where;
	$_SESSION['state']['stores']['stores']['f_field']=$f_field;
	$_SESSION['state']['stores']['stores']['f_value']=$f_value;

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


	$rtext=$total_records." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

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
	$order='`Store Code`';
	if ($order=='families')
		$order='`Store Families`';
	elseif ($order=='departments')
		$order='`Store Departments`';
	elseif ($order=='code')
		$order='`Store Code`';
	elseif ($order=='todo')
		$order='`Store In Process Products`';
	elseif ($order=='discontinued')
		$order='`Store In Process Products`';
	else if ($order=='profit') {
			if ($period=='all')
				$order='`Store Total Profit`';
			elseif ($period=='year')
				$order='`Store 1 Year Acc Profit`';
			elseif ($period=='quarter')
				$order='`Store 1 Quarter Acc Profit`';
			elseif ($period=='month')
				$order='`Store 1 Month Acc Profit`';
			elseif ($period=='week')
				$order='`Store 1 Week Acc Profit`';
		}
	elseif ($order=='sales') {
		if ($period=='all')
			$order='`Store Total Invoiced Amount`';
		elseif ($period=='year')
			$order='`Store 1 Year Acc Invoiced Amount`';
		elseif ($period=='quarter')
			$order='`Store 1 Quarter Acc Invoiced Amount`';
		elseif ($period=='month')
			$order='`Store 1 Month Acc Invoiced Amount`';
		elseif ($period=='week')
			$order='`Store 1 Week Acc Invoiced Amount`';

		elseif ($period=='yeartoday')
			$order='`Store YearToDay Acc Invoiced Amount`';
		elseif ($period=='three_year')
			$order='`Store 3 Year Acc Invoiced Amount`';
		elseif ($period=='six_month')
			$order='`Store 6 Month Acc Invoiced Amount`';
		elseif ($period=='ten_day')
			$order='`Store 10 Day Acc Invoiced Amount`';


	}
	elseif ($order=='name')
		$order='`Store Name`';
	elseif ($order=='active')
		$order='`Store For Public Sale Products`';
	elseif ($order=='outofstock')
		$order='`Store Out Of Stock Products`';
	elseif ($order=='stock_error')
		$order='`Store Unknown Stock Products`';
	elseif ($order=='surplus')
		$order='`Store Surplus Availability Products`';
	elseif ($order=='optimal')
		$order='`Store Optimal Availability Products`';
	elseif ($order=='low')
		$order='`Store Low Availability Products`';
	elseif ($order=='critical')
		$order='`Store Critical Availability Products`';
	elseif ($order=='new')
		$order='`Store New Products`';


	$sql="select sum(`Store For Public Sale Products`) as sum_active,sum(`Store Families`) as sum_families  from `Store Dimension` S $where $wheref   ";
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$sum_families=$row['sum_families'];
		$sum_active=$row['sum_active'];
	}
	mysql_free_result($result);

	global $myconf;

	if ($period=='all') {


		$sum_total_sales=0;
		$sum_month_sales=0;
		$sum_total_profit_plus=0;
		$sum_total_profit_minus=0;
		$sum_total_profit=0;
		if ($exchange_type=='day2day') {
			$sql=sprintf("select sum(if(`Store DC Total Profit`<0,`Store DC Total Profit`,0)) as total_profit_minus,sum(if(`Store DC Total Profit`>=0,`Store DC Total Profit`,0)) as total_profit_plus,sum(`Store DC Total Invoiced Amount`) as sum_total_sales  from `Store Default Currency`  S  left join `Store Dimension` SD on (`SD`.`Store Key`=`S`.`Store Key`)  %s %s",$where,$wheref);
			//  print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$sum_total_sales+=$row['sum_total_sales'];

				$sum_total_profit_plus=+$row['total_profit_plus'];
				$sum_total_profit_minus=+$row['total_profit_minus'];
				$sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
			}
			mysql_free_result($result);
		} else {
			$sql=sprintf("select sum(if(`Store Total Profit`<0,`Store Total Profit`,0)) as total_profit_minus,sum(if(`Store Total Profit`>=0,`Store Total Profit`,0)) as total_profit_plus,sum(`Store Total Invoiced Amount`) as sum_total_sales  from `Store Dimension`  S   %s %s and `Store Currency Code`!= %s ",$where,$wheref,prepare_mysql($corporate_currency));
			//print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$sum_total_sales+=$row['sum_total_sales']*$exchange_value;

				$sum_total_profit_plus+=$row['total_profit_plus']*$exchange_value;
				$sum_total_profit_minus+=$row['total_profit_minus']*$exchange_value;
				$sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
			}
			mysql_free_result($result);

		}



	}
	elseif ($period=='year') {

		$sum_total_sales=0;
		$sum_month_sales=0;
		$sum_total_profit_plus=0;
		$sum_total_profit_minus=0;
		$sum_total_profit=0;



		if ($exchange_type=='day2day') {
			$sql=sprintf("select sum(if(`Store DC 1 Year Acc Profit`<0,`Store DC 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store DC 1 Year Acc Profit`>=0,`Store DC 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Store DC 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Store Default Currency`  S left join `Store Dimension` SD on (SD.`Store Key`=S.`Store Key`)  %s %s",$where,$wheref);
			//print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$sum_total_sales+=$row['sum_total_sales'];

				$sum_total_profit_plus=+$row['total_profit_plus'];
				$sum_total_profit_minus=+$row['total_profit_minus'];
				$sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
			}
			mysql_free_result($result);
		} else {
			$sql=sprintf("select sum(if(`Store 1 Year Acc Profit`<0,`Store 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Year Acc Profit`>=0,`Store 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Store 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Store Dimension`  S   %s %s and `Store Currency Code`!= %s ",$where,$wheref,prepare_mysql($corporate_currency));
			//print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$sum_total_sales+=$row['sum_total_sales']*$exchange_value;

				$sum_total_profit_plus+=$row['total_profit_plus']*$exchange_value;
				$sum_total_profit_minus+=$row['total_profit_minus']*$exchange_value;
				$sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
			}
			mysql_free_result($result);

		}





	}
	elseif ($period=='quarter') {

		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Store 1 Quarter Acc Profit`<0,`Store 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Quarter Acc Profit`>=0,`Store 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S $where $wheref   ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);

	}
	elseif ($period=='month') {

		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Store 1 Month Acc Profit`<0,`Store 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Month Acc Profit`>=0,`Store 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S $where $wheref   ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);

	}
	elseif ($period=='week') {

		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Store 1 Week Acc Profit`<0,`Store 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Week Acc Profit`>=0,`Store 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);

	}


	elseif ($period=='yeartoday') {
		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Store YearToDay Acc Profit`<0,`Store YearToDay Acc Profit`,0)) as total_profit_minus,sum(if(`Store YearToDay Acc Profit`>=0,`Store YearToDay Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store YearToDay Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);
	}
	elseif ($period=='three_year') {
		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Store 3 Year Acc Profit`<0,`Store 3 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store 3 Year Acc Profit`>=0,`Store 3 Year Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 3 Year Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);
	}
	elseif ($period=='six_month') {
		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Store 6 Month Acc Profit`<0,`Store 6 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Store 6 Month Acc Profit`>=0,`Store 6 Month Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 6 Month Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);
	}

	elseif ($period=='ten_day') {
		$sum_total_sales=0;
		$sum_month_sales=0;
		$sql="select sum(if(`Store 10 Day Acc Profit`<0,`Store 10 Day Acc Profit`,0)) as total_profit_minus,sum(if(`Store 10 Day Acc Profit`>=0,`Store 10 Day Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 10 Day Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sum_total_sales=$row['sum_total_sales'];

			$sum_total_profit_plus=$row['total_profit_plus'];
			$sum_total_profit_minus=$row['total_profit_minus'];
			$sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
		}
		mysql_free_result($result);
	}



	$sql="select *  from `Store Dimension` S  left join `Store Default Currency` DC on DC.`Store Key`=S.`Store Key`   $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);
	$adata=array();
	$sum_sales=0;
	$sum_profit=0;
	$sum_outofstock=0;
	$sum_low=0;
	$sum_optimal=0;
	$sum_critical=0;
	$sum_surplus=0;
	$sum_unknown=0;
	$sum_departments=0;
	$sum_families=0;
	$sum_todo=0;
	$sum_discontinued=0;
	$sum_new=0;
	$DC_tag='';
	if ($exchange_type=='day2day' and $show_default_currency  )
		$DC_tag=' DC';

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Name']);
		$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);

		if ($percentages) {
			if ($period=='all') {
				$tsall=percentage($row['Store DC Total Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC Total Profit']>=0)
					$tprofit=percentage($row['Store DC Total Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC Total Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='year') {
				$tsall=percentage($row['Store DC 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC 1 Year Acc Profit']>=0)
					$tprofit=percentage($row['Store DC 1 Year Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC 1 Year Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='quarter') {
				$tsall=percentage($row['Store DC 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC 1 Quarter Acc Profit']>=0)
					$tprofit=percentage($row['Store DC 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='month') {
				$tsall=percentage($row['Store DC 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC 1 Month Acc Profit']>=0)
					$tprofit=percentage($row['Store DC 1 Month Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC 1 Month Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='week') {
				$tsall=percentage($row['Store DC 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC 1 Week Acc Profit']>=0)
					$tprofit=percentage($row['Store DC 1 Week Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC 1 Week Acc Profit'],$sum_total_profit_minus,2);
			}


			elseif ($period=='yeartoday') {
				$tsall=percentage($row['Store DC YearToDay Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC YearToDay Acc Profit']>=0)
					$tprofit=percentage($row['Store DC YearToDay Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC YearToDay Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='three_year') {
				$tsall=percentage($row['Store DC 3 Year Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC 3 Year Acc Profit']>=0)
					$tprofit=percentage($row['Store DC 3 Year Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC 3 Year Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='six_month') {
				$tsall=percentage($row['Store DC 6 Month Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC 6 Month Acc Profit']>=0)
					$tprofit=percentage($row['Store DC 6 Month Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC 6 Month Acc Profit'],$sum_total_profit_minus,2);
			}

			elseif ($period=='ten_day') {
				$tsall=percentage($row['Store DC 10 Day Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Store DC 10 Day Acc Profit']>=0)
					$tprofit=percentage($row['Store DC 10 Day Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Store DC 10 Day Acc Profit'],$sum_total_profit_minus,2);
			}




		} else {






			if ($period=="all") {


				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store Total Days On Sale"]>0)
						$factor=30.4368499/$row["Store Total Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store Total Days On Sale"]>0)
						$factor=7/$row["Store Total Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store Total Days Available"]>0)
						$factor=30.4368499/$row["Store Total Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store Total Days Available"]>0)
						$factor=7/$row["Store Total Days Available"];
					else
						$factor=0;
				}

				$tsall=($row["Store".$DC_tag." Total Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." Total Profit"]*$factor);




			}
			elseif ($period=="year") {


				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store 1 Year Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store 1 Year Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store 1 Year Acc Days On Sale"]>0)
						$factor=7/$row["Store 1 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store 1 Year Acc Days Available"]>0)
						$factor=30.4368499/$row["Store 1 Year Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store 1 Year Acc Days Available"]>0)
						$factor=7/$row["Store 1 Year Acc Days Available"];
					else
						$factor=0;
				}









				$tsall=($row["Store".$DC_tag." 1 Year Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." 1 Year Acc Profit"]*$factor);
			}
			elseif ($period=="quarter") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store 1 Quarter Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Quarter Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store 1 Quarter Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Quarter Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store 1 Quarter Acc Days On Sale"]>0)
						$factor=7/$row["Store 1 Quarter Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store 1 Quarter Acc Days Available"]>0)
						$factor=30.4368499/$row["Store 1 Quarter Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store 1 Quarter Acc Days Available"]>0)
						$factor=7/$row["Store 1 Quarter Acc Days Available"];
					else
						$factor=0;
				}


				$tsall=($row["Store".$DC_tag." 1 Quarter Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." 1 Quarter Acc Profit"]*$factor);
			}
			elseif ($period=="month") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store 1 Month Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store 1 Month Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store 1 Month Acc Days On Sale"]>0)
						$factor=7/$row["Store 1 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store 1 Month Acc Days Available"]>0)
						$factor=30.4368499/$row["Store 1 Month Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store 1 Month Acc Days Available"]>0)
						$factor=7/$row["Store 1 Month Acc Days Available"];
					else
						$factor=0;
				}


				$tsall=($row["Store".$DC_tag." 1 Month Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." 1 Month Acc Profit"]*$factor);
			}
			elseif ($period=="week") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store 1 Week Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Week Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store 1 Week Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 1 Week Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store 1 Week Acc Days On Sale"]>0)
						$factor=7/$row["Store 1 Week Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store 1 Week Acc Days Available"]>0)
						$factor=30.4368499/$row["Store 1 Week Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store 1 Week Acc Days Available"]>0)
						$factor=7/$row["Store 1 Week Acc Days Available"];
					else
						$factor=0;
				}


				$tsall=($row["Store".$DC_tag." 1 Week Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." 1 Week Acc Profit"]*$factor);
			}


			elseif ($period=="yeartoday") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store YearToDay Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store YearToDay Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store YearToDay Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store YearToDay Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store YearToDay Acc Days On Sale"]>0)
						$factor=7/$row["Store YearToDay Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store YearToDay Acc Days Available"]>0)
						$factor=30.4368499/$row["Store YearToDay Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store YearToDay Acc Days Available"]>0)
						$factor=7/$row["Store YearToDay Acc Days Available"];
					else
						$factor=0;
				}

				$tsall=($row["Store".$DC_tag." YearToDay Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." YearToDay Acc Profit"]*$factor);
			}
			elseif ($period=="three_year") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store 3 Year Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 3 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store 3 Year Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 3 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store 3 Year Acc Days On Sale"]>0)
						$factor=7/$row["Store 3 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store 3 Year Acc Days Available"]>0)
						$factor=30.4368499/$row["Store 3 Year Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store 3 Year Acc Days Available"]>0)
						$factor=7/$row["Store 3 Year Acc Days Available"];
					else
						$factor=0;
				}

				$tsall=($row["Store".$DC_tag." 3 Year Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." 3 Year Acc Profit"]*$factor);
			}
			elseif ($period=="six_month") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store 6 Month Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 6 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store 6 Month Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 6 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store 6 Month Acc Days On Sale"]>0)
						$factor=7/$row["Store 6 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store 6 Month Acc Days Available"]>0)
						$factor=30.4368499/$row["Store 6 Month Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store 6 Month Acc Days Available"]>0)
						$factor=7/$row["Store 6 Month Acc Days Available"];
					else
						$factor=0;
				}

				$tsall=($row["Store".$DC_tag." 6 Month Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." 6 Month Acc Profit"]*$factor);
			}
			elseif ($period=="ten_day") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Store 10 Day Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 10 Day Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Store 10 Day Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Store 10 Day Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Store 10 Day Acc Days On Sale"]>0)
						$factor=7/$row["Store 10 Day Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Store 10 Day Acc Days Available"]>0)
						$factor=30.4368499/$row["Store 10 Day Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Store 10 Day Acc Days Available"]>0)
						$factor=7/$row["Store 10 Day Acc Days Available"];
					else
						$factor=0;
				}

				$tsall=($row["Store".$DC_tag." 10 Day Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Store".$DC_tag." 10 Day Acc Profit"]*$factor);
			}


		}

		$sum_sales+=$tsall;
		$sum_profit+=$tprofit;
		$sum_new+=$row['Store New Products'];

		$sum_low+=$row['Store Low Availability Products'];
		$sum_optimal+=$row['Store Optimal Availability Products'];
		$sum_low+=$row['Store Low Availability Products'];
		$sum_critical+=$row['Store Critical Availability Products'];
		$sum_surplus+=$row['Store Surplus Availability Products'];
		$sum_outofstock+=$row['Store Out Of Stock Products'];
		$sum_unknown+=$row['Store Unknown Stock Products'];
		$sum_departments+=$row['Store Departments'];
		$sum_families+=$row['Store Families'];
		$sum_todo+=$row['Store In Process Products'];
		$sum_discontinued+=$row['Store Discontinued Products'];


		if (!$percentages) {
			if ($show_default_currency) {
				$class='';
				if ($corporate_currency!=$row['Store Currency Code'])
					$class='currency_exchanged';


				$sales='<span class="'.$class.'">'.money($tsall).'</span>';
				$profit='<span class="'.$class.'">'.money($tprofit).'</span>';
				$margin='<span class="'.$class.'">'.percentage($tprofit,$tsall).'</span>';
			} else {
				$sales=money($tsall,$row['Store Currency Code']);
				$profit=money($tprofit,$row['Store Currency Code']);

				$margin=percentage($tprofit,$tsall);
			}
		} else {
			$sales=$tsall;
			$profit=$tprofit;
			$margin=percentage($profit,$sales);
		}

		$adata[]=array(
			'code'=>$code,
			'name'=>$name,
			'departments'=>number($row['Store Departments']),
			'families'=>number($row['Store Families']),
			'active'=>number($row['Store For Public Sale Products']),
			'new'=>number($row['Store New Products']),
			'discontinued'=>number($row['Store Discontinued Products']),
			'outofstock'=>number($row['Store Out Of Stock Products']),
			'stock_error'=>number($row['Store Unknown Stock Products']),
			'stock_value'=>money($row['Store Stock Value']),
			'surplus'=>number($row['Store Surplus Availability Products']),
			'optimal'=>number($row['Store Optimal Availability Products']),
			'low'=>number($row['Store Low Availability Products']),
			'critical'=>number($row['Store Critical Availability Products']),
			'sales'=>$sales,
			'profit'=>$profit,
			'margin'=>$margin
		);
	}
	mysql_free_result($res);


	if ($total<=$number_results) {

		if ($percentages) {
			$sum_sales='100.00%';
			$sum_profit='100.00%';
			$margin=percentage($sum_total_profit,$sum_total_sales);

		} else {
			$sum_sales=money($sum_total_sales);
			$sum_profit=money($sum_total_profit);
			$margin=percentage($sum_total_profit,$sum_total_sales);
		}
		$sum_new=number($sum_new);
		$sum_outofstock=number($sum_outofstock);
		$sum_low=number($sum_low);
		$sum_optimal=number($sum_optimal);
		$sum_critical=number($sum_critical);
		$sum_surplus=number($sum_surplus);
		$sum_unknown=number($sum_unknown);
		$sum_departments=number($sum_departments);
		$sum_families=number($sum_families);
		$sum_todo=number($sum_todo);
		$sum_discontinued=number($sum_discontinued);
		$adata[]=array(
			'name'=>'',
			'code'=>_('Total'),
			'active'=>number($sum_active),
			'sales'=>$sum_sales,
			'profit'=>$sum_profit,
			'margin'=>$margin,
			'todo'=>$sum_todo,
			'discontinued'=>$sum_discontinued,
			'low'=>$sum_low,
			'new'=>$sum_new,
			'critical'=>$sum_critical,
			'surplus'=>$sum_surplus,
			'optimal'=>$sum_optimal,
			'outofstock'=>$sum_outofstock,
			'stock_error'=>$sum_unknown,
			'departments'=>$sum_departments,
			'families'=>$sum_families
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

	if (isset( $_REQUEST['y']))
		$y=$_REQUEST['y'];
	else
		$y=$conf['y'];


	if (isset( $_REQUEST['m']))
		$m=$_REQUEST['m'];
	else
		$m=$conf['m'];

	$_SESSION['state'][$conf_table]['pages']['y']=$y;
	$_SESSION['state'][$conf_table]['pages']['m']=$m;
	$_SESSION['state'][$conf_table]['pages']['order']=$order;
	$_SESSION['state'][$conf_table]['pages']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['pages']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['pages']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['pages']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['pages']['f_value']=$f_value;

	$_order=$order;
	$_dir=$order_direction;

	
	

	$where=sprintf("where `Current Dispatching State`='Dispatched' and `Invoice Date`>='%d-%02d-01 00:00:00'  and `Invoice Date`<'%d-%02d-01 00:00:00'  and `Destination Country 2 Alpha Code` in ('AT','BE','BG','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES') ",
	$y,$m,($m==12?$y+1:$y),($m==12?1:$m+1)
	);


	$wheref='';
	if ($f_field=='tariff_code'  and $f_value!='')
		$wheref.=" and `Product Tariff Code` like '".addslashes($f_value)."%'";



	$sql="select `Product Tariff Code` from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  $where $wheref group by `Product Tariff Code`,`Destination Country 2 Alpha Code` ";

	$result=mysql_query($sql);



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

	$rtext=$total_records." ".ngettext('page','pages',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';





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

	$sql="select  sum(`Shipped Quantity`*`Product Units Per Case`) as items,sum(`Order Bonus Quantity`) as bonus,GROUP_CONCAT(DISTINCT ' <a href=\"invoice.php?id=',`Invoice Key`,'\">',`Invoice Public ID`,'</a>' ) as invoices ,sum(`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)) as value , sum(`Shipped Quantity`*`Product Net Weight`) as weight , LEFT(`Product Tariff Code`,8) as tariff_code, date_format(`Invoice Date`,'%y%m') as monthyear ,`Destination Country 2 Alpha Code` from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  $where $wheref group by `Product Tariff Code`,`Destination Country 2 Alpha Code`  order by   $order $order_dir  limit $start_from,$number_results";
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
	$number_out_of_stock_dn=0;
	$number_parts=0;
	$number_dn=0;
	
	
	$sql=sprintf("select count(DISTINCT `Part SKU`) as number_out_of_stock_parts, count(DISTINCT `Delivery Note Key`) as number_out_of_stock_dn from `Inventory Transaction Fact`  where `Inventory Transaction Type`='Sale'  and  `Out of Stock`>0  %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_out_of_stock_parts=$row['number_out_of_stock_parts'];
		$number_out_of_stock_dn=$row['number_out_of_stock_dn'];

	}
	
		$sql=sprintf("select count(DISTINCT `Part SKU`) as parts, count(DISTINCT `Delivery Note Key`) as dn from `Inventory Transaction Fact`  where `Inventory Transaction Type`='Sale'   %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_parts=$row['parts'];
		$number_dn=$row['dn'];

	}
	$number_out_of_stock_parts=number($number_out_of_stock_parts).' ('.percentage($number_out_of_stock_parts,$number_parts).')';
		$number_out_of_stock_dn=number($number_out_of_stock_dn).' ('.percentage($number_out_of_stock_dn,$number_dn).')';

	$response=array('state'=>200,'number_out_of_stock_parts'=>$number_out_of_stock_parts,'number_out_of_stock_dn'=>$number_out_of_stock_dn);
	echo json_encode($response);

}


function out_of_stock_customer_data($data) {

	$from=$data['from'];
	$to=$data['to'];
	$date_interval=prepare_mysql_dates($from.' 00:00:00',$to.' 23:59:59','`Actual Shipping Date`');
	$number_out_of_stock_customers=0;
	$number_customers=0;
	
	
	$sql=sprintf("select count(DISTINCT `Customer Key`) as number_out_of_stock_customers from `Order Transaction Fact`  where `Current Dispatching State`='Dispatched'  and  `No Shipped Due Out of Stock`>0  %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_out_of_stock_customers=$row['number_out_of_stock_customers'];

	}
	
	$sql=sprintf("select count(DISTINCT `Customer Key`) as customers from `Order Transaction Fact`  where `Current Dispatching State`='Dispatched' %s ",$date_interval['mysql']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$number_customers=$row['customers'];

	}
	$number_out_of_stock_customers=number($number_out_of_stock_customers).' ('.percentage($number_out_of_stock_customers,$number_customers).')';

	$response=array('state'=>200,'number_out_of_stock_customers'=>$number_out_of_stock_customers);
	echo json_encode($response);

}



?>
