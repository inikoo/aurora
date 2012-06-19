<?php
require_once 'common.php';

if (!isset($output_type))
	$output_type='ajax';



if (!isset($_REQUEST['tipo'])) {
	if ($output_type=='ajax') {
		$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
		echo json_encode($response);
	}
	return;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('customers'):
	$results=list_customers();
	break;
case('products'):
	if ($_REQUEST['type']=='products')
		$results=list_products();
	else
		$results=list_families();
	break;
case('sales'):

	if (isset( $_REQUEST['type'])) {
		$type=$_REQUEST['type'];
		$_SESSION['state']['home']['splinters']['sales']['type']=$type;
	} else
		$type=$_SESSION['state']['home']['splinters']['sales']['type'];
	if ($type=='stores')
		$results=store_sales_overview();
	else
		$results=invoice_categories_sales_overview();

	break;
case('orders_in_process'):
	orders_in_process();
	break;
}


function invoice_categories_sales_overview() {


	global $myconf,$output_type,$user,$corporate_currency_symbol;

	$conf=$_SESSION['state']['home']['splinters']['sales'];
	$start_from=0;





	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['home']['splinters']['sales']['period']=$period;
	} else
		$period=$conf['period'];





	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$store=join(',',$user->stores);




	$filter_msg='';
	$wheref='';

	if (!$store)
		$where=sprintf(' and false ');

	else
		$where=sprintf(' and C.`Category Store Key` in (%s) ',$store);




	$filtered=0;
	$rtext='';
	$total=0;


	switch ($period) {
	case('1w'):
		$fields=sprintf(" `1 Week Acc Invoices` as invoices,`1 Week Acc Invoiced Amount` as sales, `1 Week Acc 1YB Invoices` as invoices_1yb,`1 Week Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC 1 Week Acc Invoiced Amount` as dc_sales,`DC 1 Week Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('10d'):
		$fields=sprintf(" `10 Day Acc Invoices` as invoices,`10 Day Acc Invoiced Amount` as sales, `10 Day Acc 1YB Invoices` as invoices_1yb,`10 Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC 10 Day Acc Invoiced Amount` as dc_sales,`DC 10 Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('1m'):
		$fields=sprintf(" `1 Month Acc Invoices` as invoices,`1 Month Acc Invoiced Amount` as sales, `1 Month Acc 1YB Invoices` as invoices_1yb,`1 Month Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC 1 Month Acc Invoiced Amount` as dc_sales,`DC 1 Month Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('1q'):
		$fields=sprintf(" `1 Quarter Acc Invoices` as invoices,`1 Quarter Acc Invoiced Amount` as sales, `1 Quarter Acc 1YB Invoices` as invoices_1yb,`1 Quarter Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC 1 Quarter Acc Invoiced Amount` as dc_sales,`DC 1 Quarter Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('1y'):
		$fields=sprintf(" `1 Year Acc Invoices` as invoices,`1 Year Acc Invoiced Amount` as sales, `1 Year Acc 1YB Invoices` as invoices_1yb,`1 Year Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC 1 Year Acc Invoiced Amount` as dc_sales,`DC 1 Year Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('3y'):
		$fields=sprintf(" `3 Year Acc Invoices` as invoices,`3 Year Acc Invoiced Amount` as sales, `3 Year Acc Invoices` as invoices_1yb,`3 Year Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC 3 Year Acc Invoiced Amount` as dc_sales,`DC 3 Year Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('yesterday'):
		$fields=sprintf(" `Yesterday Acc Invoices` as invoices,`Yesterday Acc Invoiced Amount` as sales, `Yesterday Acc 1YB Invoices` as invoices_1yb,`Yesterday Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC Yesterday Acc Invoiced Amount` as dc_sales,`DC Yesterday Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('last_m'):
		$fields=sprintf(" `Last Month Acc Invoices` as invoices,`Last Month Acc Invoiced Amount` as sales, `Last Month Acc 1YB Invoices` as invoices_1yb,`Last Month Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC Last Month Acc Invoiced Amount` as dc_sales,`DC Last Month Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('last_w'):
		$fields=sprintf(" `Last Week Acc Invoices` as invoices,`Last Week Acc Invoiced Amount` as sales, `Last Week Acc 1YB Invoices` as invoices_1yb,`Last Week Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC Last Week Acc Invoiced Amount` as dc_sales,`DC Last Week Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('today'):
		$fields=sprintf(" `Today Acc Invoices` as invoices,`Today Acc Invoiced Amount` as sales, `Today Acc 1YB Invoices` as invoices_1yb,`Today Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC Today Acc Invoiced Amount` as dc_sales,`DC Today Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;

	case('wtd'):
		$fields=sprintf(" `Week To Day Acc Invoices` as invoices,`Week To Day Acc Invoiced Amount` as sales, `Week To Day Acc 1YB Invoices` as invoices_1yb,`Week To Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC Week To Day Acc Invoiced Amount` as dc_sales,`DC Week To Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('mtd'):
		$fields=sprintf(" `Month To Day Acc Invoices` as invoices,`Month To Day Acc Invoiced Amount` as sales, `Month To Day Acc 1YB Invoices` as invoices_1yb,`Month To Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC Month To Day Acc Invoiced Amount` as dc_sales,`DC Month To Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('ytd'):
		$fields=sprintf(" `Year To Day Acc Invoices` as invoices,`Year To Day Acc Invoiced Amount` as sales, `Year To Day Acc 1YB Invoices` as invoices_1yb,`Year To Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `DC Year To Day Acc Invoiced Amount` as dc_sales,`DC Year To Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;

	default:
		$fields=sprintf(" `1 Week Acc Invoices` as invoices,`1 Week Acc Invoiced Amount` as sales " );
	}

	$sql=sprintf("select  IC.`Category Key`,`Category Label`, `Category Store Key`,`Store Currency Code` currency,%s from `Invoice Category Dimension` IC left join `Category Dimension` C on (C.`Category Key`=IC.`Category Key`) left join `Store Dimension` S on (S.`Store Key`=C.`Category Store Key`) ",
		$fields);
	$adata=array();
	//print $sql;
	$position=1;
	$result=mysql_query($sql);
	$sum_invoices=0;
	$sum_invoices_1yb=0;
	$sum_dc_sales=0;
	$sum_dc_sales_1yb=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total++;
		$sum_invoices+=$row['invoices'];
		$sum_invoices_1yb+=$row['invoices_1yb'];

		$sum_dc_sales+=$row['dc_sales'];
		$sum_dc_sales_1yb+=$row['dc_sales_1yb'];

		$invoice=number($row['invoices']);
		$category="<a target='_parent' href='report_sales.php?invoice_category_key=".$row['Category Key']."'>".$row['Category Label'].'</a>';
		//$invoices=sprintf('<a href="orders.php?view=invoices&invoice_type=invoices&splinter=1&cat_key=%d">%s</a>',$row['Category Key'], $invoice);
		$invoices=sprintf('<a target="_parent" href="new_invoices_list.php?store=%d&period=%s&auto=1&cat_key=%d">%s</a>',$row['Category Store Key'],$period,$row['Category Key'],$invoice);
		$adata[]=array(

			'store'=>$category,
			'invoices'=>$invoices,
			'invoices_1yb'=>number($row['invoices_1yb']),
			'invoices_delta'=>delta($row['invoices'],$row['invoices_1yb']),
			'invoices_share'=>$row['invoices'],
			'sales'=>money($row['sales'],$row['currency']),
			'sales_1yb'=>money($row['sales_1yb'],$row['currency']),
			'sales_delta'=>delta($row['sales'],$row['sales_1yb']),
			'dc_sales'=>money($row['dc_sales'],$corporate_currency_symbol),
			'dc_sales_1yb_'=>money($row['dc_sales_1yb'],$corporate_currency_symbol),
			'dc_sales_delta'=>delta($row['dc_sales'],$row['dc_sales_1yb']),
			'dc_sales_share'=>$row['dc_sales']



		);
	}
	mysql_free_result($result);

	foreach ($adata as $key=>$value) {

		$adata[$key]['invoices_share']=percentage($adata[$key]['invoices_share'],$sum_invoices);
		$adata[$key]['dc_sales_share']=percentage($adata[$key]['dc_sales_share'],$sum_dc_sales);

	}

	$adata[]=array(

		'store'=>_('Total'),
		'invoices'=>number($sum_invoices),
		'invoices_1yb'=>'',
		'invoices_delta'=>delta($sum_invoices,$sum_invoices_1yb),
		'sales'=>'',
		'sales_delta'=>'',
		'dc_sales'=>money($sum_dc_sales,$corporate_currency_symbol),
		'dc_sales_delta'=>delta($sum_dc_sales,$sum_dc_sales_1yb),
		'invoices_share'=>''
	);


	$rtext_rpp='';
	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>'store',
			'sort_dir'=>'desc',
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$total,

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

function store_sales_overview() {


	global $myconf,$output_type,$user,$corporate_currency_symbol;

	$conf=$_SESSION['state']['home']['splinters']['sales'];
	$start_from=0;





	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['home']['splinters']['sales']['period']=$period;
	} else
		$period=$conf['period'];





	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$store=join(',',$user->stores);




	$filter_msg='';
	$wheref='';

	if (!$store)
		$where=sprintf(' and false ');

	else
		$where=sprintf(' and S.`Store Key` in (%s) ',$store);




	$filtered=0;
	$rtext='';
	$total=0;


	switch ($period) {
	case('1w'):
		$fields=sprintf(" `Store 1 Week Acc Invoices` as invoices,`Store 1 Week Acc Invoiced Amount` as sales, `Store 1 Week Acc 1YB Invoices` as invoices_1yb,`Store 1 Week Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC 1 Week Acc Invoiced Amount` as dc_sales,`Store DC 1 Week Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('10d'):
		$fields=sprintf(" `Store 10 Day Acc Invoices` as invoices,`Store 10 Day Acc Invoiced Amount` as sales, `Store 10 Day Acc 1YB Invoices` as invoices_1yb,`Store 10 Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC 10 Day Acc Invoiced Amount` as dc_sales,`Store DC 10 Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('1m'):
		$fields=sprintf(" `Store 1 Month Acc Invoices` as invoices,`Store 1 Month Acc Invoiced Amount` as sales, `Store 1 Month Acc 1YB Invoices` as invoices_1yb,`Store 1 Month Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC 1 Month Acc Invoiced Amount` as dc_sales,`Store DC 1 Month Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('1q'):
		$fields=sprintf(" `Store 1 Quarter Acc Invoices` as invoices,`Store 1 Quarter Acc Invoiced Amount` as sales, `Store 1 Quarter Acc 1YB Invoices` as invoices_1yb,`Store 1 Quarter Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC 1 Quarter Acc Invoiced Amount` as dc_sales,`Store DC 1 Quarter Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('1y'):
		$fields=sprintf(" `Store 1 Year Acc Invoices` as invoices,`Store 1 Year Acc Invoiced Amount` as sales, `Store 1 Year Acc 1YB Invoices` as invoices_1yb,`Store 1 Year Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC 1 Year Acc Invoiced Amount` as dc_sales,`Store DC 1 Year Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('3y'):
		$fields=sprintf(" `Store 3 Year Acc Invoices` as invoices,`Store 3 Year Acc Invoiced Amount` as sales, `Store 3 Year Acc 1YB Invoices` as invoices_1yb,`Store 3 Year Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC 3 Year Acc Invoiced Amount` as dc_sales,`Store DC 3 Year Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('yesterday'):
		$fields=sprintf(" `Store Yesterday Acc Invoices` as invoices,`Store Yesterday Acc Invoiced Amount` as sales, `Store Yesterday Acc 1YB Invoices` as invoices_1yb,`Store Yesterday Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC Yesterday Acc Invoiced Amount` as dc_sales,`Store DC Yesterday Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('last_m'):
		$fields=sprintf(" `Store Last Month Acc Invoices` as invoices,`Store Last Month Acc Invoiced Amount` as sales, `Store Last Month Acc 1YB Invoices` as invoices_1yb,`Store Last Month Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC Last Month Acc Invoiced Amount` as dc_sales,`Store DC Last Month Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('last_w'):
		$fields=sprintf(" `Store Last Week Acc Invoices` as invoices,`Store Last Week Acc Invoiced Amount` as sales, `Store Last Week Acc 1YB Invoices` as invoices_1yb,`Store Last Week Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC Last Week Acc Invoiced Amount` as dc_sales,`Store DC Last Week Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('today'):
		$fields=sprintf(" `Store Today Acc Invoices` as invoices,`Store Today Acc Invoiced Amount` as sales, `Store Today Acc 1YB Invoices` as invoices_1yb,`Store Today Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC Today Acc Invoiced Amount` as dc_sales,`Store DC Today Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;

	case('wtd'):
		$fields=sprintf(" `Store Week To Day Acc Invoices` as invoices,`Store Week To Day Acc Invoiced Amount` as sales, `Store Week To Day Acc 1YB Invoices` as invoices_1yb,`Store Week To Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC Week To Day Acc Invoiced Amount` as dc_sales,`Store DC Week To Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('mtd'):
		$fields=sprintf(" `Store Month To Day Acc Invoices` as invoices,`Store Month To Day Acc Invoiced Amount` as sales, `Store Month To Day Acc 1YB Invoices` as invoices_1yb,`Store Month To Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC Month To Day Acc Invoiced Amount` as dc_sales,`Store DC Month To Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;
	case('ytd'):
		$fields=sprintf(" `Store Year To Day Acc Invoices` as invoices,`Store Year To Day Acc Invoiced Amount` as sales, `Store Year To Day Acc 1YB Invoices` as invoices_1yb,`Store Year To Day Acc 1YB Invoiced Amount` as sales_1yb,
                        `Store DC Year To Day Acc Invoiced Amount` as dc_sales,`Store DC Year To Day Acc 1YB Invoiced Amount` as dc_sales_1yb
                        " );
		break;

	default:
		$fields=sprintf(" `Store 1 Week Acc Invoices` as invoices,`Store 1 Week Acc Invoiced Amount` as sales " );
	}

	$sql=sprintf("select  S.`Store Key`,`Store Name`, `Store Currency Code` currency,%s from `Store Dimension` S left join `Store Default Currency` DC on (S.`Store Key`=DC.`Store Key`) ",$fields);
	$adata=array();
	//print $sql;
	$position=1;
	$result=mysql_query($sql);
	$sum_invoices=0;
	$sum_invoices_1yb=0;
	$sum_dc_sales=0;
	$sum_dc_sales_1yb=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total++;
		$sum_invoices+=$row['invoices'];
		$sum_invoices_1yb+=$row['invoices_1yb'];

		$sum_dc_sales+=$row['dc_sales'];
		$sum_dc_sales_1yb+=$row['dc_sales_1yb'];

		$invoice=number($row['invoices']);
		$store="<a href='report_sales.php?store_key=".$row['Store Key']."'>".$row['Store Name'].'</a>';
		$invoices=sprintf('<a href="new_invoices_list.php?store=%d&period=%s&auto=1">%s</a>',$row['Store Key'],$period,$invoice);

		$adata[]=array(

			'store'=>$store,
			'invoices'=>$invoices,
			'invoices_1yb'=>number($row['invoices_1yb']),
			'invoices_delta'=>delta($row['invoices'],$row['invoices_1yb']),
			'invoices_share'=>$row['invoices'],
			'sales'=>money($row['sales'],$row['currency']),
			'sales_1yb'=>money($row['sales_1yb'],$row['currency']),
			'sales_delta'=>delta($row['sales'],$row['sales_1yb']),
			'dc_sales'=>money($row['dc_sales'],$corporate_currency_symbol),
			'dc_sales_1yb_'=>money($row['dc_sales_1yb'],$corporate_currency_symbol),
			'dc_sales_delta'=>delta($row['dc_sales'],$row['dc_sales_1yb']),
			'dc_sales_share'=>$row['dc_sales']



		);
	}
	mysql_free_result($result);

	foreach ($adata as $key=>$value) {

		$adata[$key]['invoices_share']=percentage($adata[$key]['invoices_share'],$sum_invoices);
		$adata[$key]['dc_sales_share']=percentage($adata[$key]['dc_sales_share'],$sum_dc_sales);

	}

	$adata[]=array(

		'store'=>_('Total'),
		'invoices'=>number($sum_invoices),
		'invoices_1yb'=>'',
		'invoices_delta'=>delta($sum_invoices,$sum_invoices_1yb),
		'sales'=>'',
		'sales_delta'=>'',
		'dc_sales'=>money($sum_dc_sales,$corporate_currency_symbol),
		'dc_sales_delta'=>delta($sum_dc_sales,$sum_dc_sales_1yb),
		'invoices_share'=>''
	);

	$rtext_rpp='';

	$response=array('resultset'=>
		array(
			'state'=>200,
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





function list_families() {


	global $myconf,$output_type,$user;
	$_SESSION['state']['home']['splinters']['top_products']['type']='families';
	$conf=$_SESSION['state']['home']['splinters']['top_products'];
	//print_r($conf);
	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['home']['splinters']['top_products']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['home']['splinters']['top_products']['order']=$order;
	} else
		$order=$conf['order'];
	$order_direction='desc';
	$order_dir='desc';

	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['home']['splinters']['top_products']['period']=$period;
	} else
		$period=$conf['period'];





	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$store=join(',',$user->stores);
	if ($store=='')$store=0;




	$filter_msg='';
	$wheref='';

	if (!$store)
		$where=sprintf(' and false ');

	else
		$where=sprintf(' and `Product Family Store Key` in (%s) ',$store);




	$filtered=0;
	$rtext='';
	$total=$number_results;



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='profits')
		$order='`Product Family 1 Year Acc Profit`';

	else {
		switch ($period) {
		case('all'):
			$order='`Product Family Total Invoiced Amount`';
			break;
		case('1m'):
			$order='`Product Family 1 Month Acc Invoiced Amount`';
			break;
		case('1y'):
			$order='`Product Family 1 Year Acc Invoiced Amount`';
			break;
		case('1q'):
			$order='`Product Family 1 Quarter Acc Invoiced Amount`';
			break;
		default:
			$order='`Product Family 1 Year Acc Invoiced Amount`';

		}

	}

	$sql="select  *  from `Product Family Dimension` P  left join `Store Dimension` S on (P.`Product Family Store Key`=S.`Store Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results";
	$adata=array();
	//print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


		switch ($period) {
		case('all'):
			$sales=money($data['Product Family Total Invoiced Amount'],$data['Store Currency Code']);
			break;
		case('1m'):
			$sales=money($data['Product Family 1 Month Acc Invoiced Amount'],$data['Store Currency Code']);
			break;
		case('1y'):
			$sales=money($data['Product Family 1 Year Acc Invoiced Amount'],$data['Store Currency Code']);
			break;
		case('1q'):
			$sales=money($data['Product Family 1 Quarter Acc Invoiced Amount'],$data['Store Currency Code']);
			break;
		default:
			$sales=money($data['Product Family 1 Year Acc Invoiced Amount'],$data['Store Currency Code']);

		}


		$family="<a href='family.php?id=".$data['Product Family Key']."'>".$data['Product Family Code'].'</a>';
		$store="<a href='store.php?id=".$data['Product Family Store Key']."'>".$data['Store Code'].'</a>';
		$family_description="<a href='family.php?id=".$data['Product Family Key']."'>".$data['Product Family Name'].'</a>';
		$adata[]=array(
			'position'=>'<b>'.$position++.'</b>',
			'family_description'=>$family_description,
			'family'=>$family,
			'store'=>$store,
			'description'=>$data['Product Family Name'],
			'net_sales'=>$sales
		);
	}
	mysql_free_result($result);




	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$number_results,
			'sort_key'=>'position',
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
function list_products() {


	global $myconf,$output_type,$user;
	$_SESSION['state']['home']['splinters']['top_products']['type']='products';

	$conf=$_SESSION['state']['home']['splinters']['top_products'];
	//print_r($conf);
	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['home']['splinters']['top_products']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['home']['splinters']['top_products']['order']=$order;
	} else
		$order=$conf['order'];
	$order_direction='desc';
	$order_dir='desc';

	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['home']['splinters']['top_products']['period']=$period;
	} else
		$period=$conf['period'];





	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;



	$store=join(',',$user->stores);
	if ($store=='')$store=0;




	$filter_msg='';
	$wheref='';

	if (!$store)
		$where=sprintf(' and false ');

	else
		$where=sprintf(' and `Product Store Key` in (%s) ',$store);




	$filtered=0;
	$rtext='';
	$total=$number_results;



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='profits')
		$order='`Product 1 Year Acc Profit`';

	else {
		switch ($period) {
		case('all'):
			$order='`Product Total Acc Invoiced Amount`';
			break;
		case('1m'):
			$order='`Product 1 Month Acc Invoiced Amount`';
			break;
		case('1y'):
			$order='`Product 1 Year Acc Invoiced Amount`';
			break;
		case('1q'):
			$order='`Product 1 Quarter Acc Invoiced Amount`';
			break;
		default:
			$order='`Product 1 Year Acc Invoiced Amount`';

		}

	}

	$sql="select  * from `Product Dimension` P  left join `Store Dimension` S on (P.`Product Store Key`=S.`Store Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results";
	$adata=array();
	// print $sql;
	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


		switch ($period) {
		case('all'):
			$sales=money($data['Product Total Acc Invoiced Amount'],$data['Store Currency Code']);
			break;
		case('1m'):
			$sales=money($data['Product 1 Month Acc Invoiced Amount'],$data['Store Currency Code']);
			break;
		case('1y'):
			$sales=money($data['Product 1 Year Acc Invoiced Amount'],$data['Store Currency Code']);
			break;
		case('1q'):
			$sales=money($data['Product 1 Quarter Acc Invoiced Amount'],$data['Store Currency Code']);
			break;
		default:
			$sales=money($data['Product 1 Year Acc Invoiced Amount'],$data['Store Currency Code']);

		}


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
			'rtext_rpp'=>$number_results,
			'sort_key'=>'position',
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

	$conf=$_SESSION['state']['home']['splinters']['top_customers'];

	$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		$_SESSION['state']['home']['splinters']['top_customers']['nr']=$number_results;
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o'])) {
		$order=$_REQUEST['o'];
		$_SESSION['state']['home']['splinters']['top_customers']['order']=$order;
	} else
		$order=$conf['order'];


	$order_direction='desc';
	$order_dir='desc';

	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['home']['splinters']['top_customers']['period']=$period;
	} else
		$period=$conf['period'];



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	/*
       if(isset( $_REQUEST['store_keys'])    ){
         $store=$_REQUEST['store_keys'];
         $_SESSION['state']['home']['splinters']['top_customers']['store_keys']=$store;
       }else
         $store=$_SESSION['state']['home']['splinters']['top_customers']['store_keys'];

       if($store=='all'){
          $store=join(',',$user->stores);

       }
       */
	$store=$user->stores;



	$store=join(',',$user->stores);
	if ($store=='')$store=0;

	$filter_msg='';
	$wheref='';

	/*
         if($store=='')
              $where=sprintf(' where false ');

         else
         $where=sprintf(' where `Customer Orders Invoiced`>=0 and `Invoice Store Key` in (%s) ',$store);
    */


	$filtered=0;
	$rtext='';
	$total=$number_results;


	switch ($period) {

	case('1m'):
		$where=sprintf(" where `Customer Orders Invoiced`>=0 and `Invoice Store Key` in (%s) and  `Invoice Date`>=%s",$store,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month"))));
		break;
	case('1y'):
		$where=sprintf(" where `Customer Orders Invoiced`>=0 and `Invoice Store Key` in (%s)  and `Invoice Date`>=%s",$store,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year"))));

		break;
	case('1q'):
		$where=sprintf("where `Customer Orders Invoiced`>=0 and `Invoice Store Key` in (%s)  and `Invoice Date`>=%s",$store,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 months"))));

		break;
	default:
		$where=sprintf(' where `Customer Orders Invoiced`>0 and `Customer Store Key` in (%s) ',$store);


	}



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='invoices')
		$order='`Invoices`';

	else
		$order=' net_balance ';








	if ($period=='all')
		$sql="select  `Customer Net Balance`*`Invoice Currency Exchange` as  net_balance , `Store Code`,`Customer Type by Activity`,`Customer Last Order Date`,`Customer Main XHTML Telephone`,`Customer Key`,`Customer Name`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main Town`,`Customer Main Country First Division`,`Customer Main Delivery Address Postal Code`,`Customer Orders Invoiced` as Invoices , `Customer Net Balance` as Balance  from `Customer Dimension` C  left join `Store Dimension` SD on (C.`Customer Store Key`=SD.`Store Key`)  $where $wheref  group by `Customer Key` order by $order $order_direction limit $start_from,$number_results";
	else
		$sql="select  sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`)  as  net_balance , `Invoice Currency`,`Store Code`,`Customer Type by Activity`,`Customer Last Order Date`,`Customer Main XHTML Telephone`,C.`Customer Key`,`Customer Name`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main Town`,`Customer Main Country First Division`,`Customer Main Delivery Address Postal Code`,count(distinct `Invoice Key`) as Invoices    from  `Invoice Dimension` I left join   `Customer Dimension` C on (`Invoice Customer Key`=C.`Customer Key`) left join `Store Dimension` SD on (C.`Customer Store Key`=SD.`Store Key`)  $where $wheref  group by `Invoice Customer Key` order by $order $order_direction limit $start_from,$number_results";


	//print $sql;
	$adata=array();


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
			'net_balance'=>money($data['net_balance']),
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
function orders_in_process() {

	$conf=$_SESSION['state']['home']['splinters']['orders_in_process'];
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

	$_SESSION['state']['report']['orders_in_progress']['order']=$order;
	$_SESSION['state']['report']['orders_in_progress']['order_dir']=$order_direction;
	$_SESSION['state']['report']['orders_in_progress']['sf']=$start_from;
	$_SESSION['state']['report']['orders_in_progress']['nr']=$number_results;



	$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
	if ($date_interval['error']) {
		$date_interval=prepare_mysql_dates($conf['from'],$conf['to']);
	} else {
		$_SESSION['state']['report']['orders_in_progress']['from']=$date_interval['from'];
		$_SESSION['state']['report']['orders_in_progress']['to']=$date_interval['to'];
	}

	$output_type='ajax';
	$filtered=0;
	$rtext='';

	$wheref='';


	$where=' where `Order Current Dispatch State`="In Process"';


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';

	$sql="select count(*) as total from `Order Dimension` $where $wheref  ";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total_records=$row['total'];

	} else
		$total_records=0;


	$rtext=$total_records." ".ngettext('order','orders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=" ("._("showing all records").")";



	if ($order=='invoices')
		$order='`Invoices`';

	else
		$order='`Order Date`';


	$sql="select  `Order Date`,`Order Currency`,`Order Total Net Amount`+`Order Total Tax Amount` as `Order Total`,C.`Customer Key`, `Customer Name`,`Order Public ID`,`Order Key` from `Order Dimension` O left join `Customer Dimension` C on (O.`Order Customer Key`=C.`Customer Key`) $where $wheref  order by  $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$adata=array();


	$position=1;
	$result=mysql_query($sql);
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {







		$order="<a href='order.php?id=".$data['Order Key']."'>".$data['Order Public ID'].'</a>';
		$customer="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';

		$adata[]=array(
			'order'=>$order
			,'customer'=>$customer
			,'value'=>money($data['Order Total'],$data['Order Currency'])
			,'date'=>strftime("%x",strtotime($data['Order Date']))
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
			'total_records'=>$total_records,
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



?>
