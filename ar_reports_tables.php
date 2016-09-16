<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:27:49 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'reports':
	reports(get_table_parameters(), $db, $user);
	break;
case 'ec_sales_list':
	ec_sales_list(get_table_parameters(), $db, $user, $account);
	break;
case 'billingregion_taxcategory':
	billingregion_taxcategory(get_table_parameters(), $db, $user, $account);
	break;
case 'billingregion_taxcategory.invoices':
case 'billingregion_taxcategory.refunds':
	invoices_billingregion_taxcategory(get_table_parameters(), $db, $user, $account);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function reports($_data, $db, $user) {
	global $db;
	$rtext_label='report';
	//include_once 'prepare_table/init.php';
	include_once 'utils/available_reports.php';



	//$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


	$adata=array();




	foreach ($available_reports as $key=>$data) {

		$adata[]=array(
			'name'=>$data['Label'],
			'report_request'=>'report/'.$key,
			'section'=>$data['GroupLabel'],
			'section_request'=>'reports/'.$data['Group']

		);

	}

	$_order=(isset($_data['o'])  ?$_data['o']:'id');
	$_dir=((isset($_data['od']) and  preg_match('/desc/i', $_data['od']) ) ?'desc':'');




	$rtext=get_rtext('report', count($available_reports));

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> count($available_reports)

		)
	);
	echo json_encode($response);
}


function ec_sales_list($_data, $db, $user, $account) {

	$rtext_label='record';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

//print $sql;

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {


			switch ($data['Invoice Tax Number Valid']) {
			case 'Yes':
				$tax_number_valid='<i class="fa fa-check-circle success padding_right_5" aria-hidden="true"></i> ';
				break;
			case 'No':
				$tax_number_valid='<i class="fa fa-exclamation-circle error padding_right_5" aria-hidden="true"></i> ';
				break;
			case 'Unknown':
				$tax_number_valid='<i class="fa fa-question-circle very_discreet padding_right_5" aria-hidden="true"></i> ';
				break;
			default:
				$tax_number_valid='';
				break;
			}

			if ($data['Invoice Tax Number']=='') {
				$tax_number_valid='';
			}

			$tax_number=$data['Invoice Tax Number'];
			$country_2alpha_code=$data['Invoice Billing Country 2 Alpha Code'];
			$tax_number=preg_replace('/^'.$country_2alpha_code.'/i', '', $tax_number);
			$tax_number=preg_replace('/[^a-z^0-9]/i', '', $tax_number);

			if (preg_match('/^gr$/i', $country_2alpha_code)) {
				$country_2alpha_code='EL';
			}

			$tax_number=preg_replace('/^'.$country_2alpha_code.'/i', '', $tax_number);
			$tax_number=preg_replace('/[^a-z^0-9]/i', '', $tax_number);

			$adata[]=array(

				// 'tax_code'=> sprintf('<span title="%s">%s</span>', ($data['Invoice Tax Code']=='UNK'?_('Unknown tax code'):$data['Tax Category Name']), $data['Invoice Tax Code']),
				// 'request'=>$data['Invoice Billing Region'].'/'.$data['Invoice Tax Code'],
				'country_code'=>$data['Invoice Billing Country 2 Alpha Code'],
				'invoices'=>number($data['invoices'])  ,
				'refunds'=>number($data['refunds']) ,
				'customer'=>sprintf('<span class="link" onClick="change_view(\'customer/%d\')"   title="%s">%06d</span>', $data['Invoice Customer Key'], $data['Invoice Customer Name'], $data['Invoice Customer Key']) ,
				'tax_number'=>$tax_number_valid.$tax_number ,
				'tax'=>money($data['tax'], $account->get('Account Currency')) ,
				'net'=>money($data['net'], $account->get('Account Currency')) ,
				'total'=>money($data['total'], $account->get('Account Currency')) ,


			);

		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


$sql="select $fields from $table $where $wheref $group_by";
	$stmt=$db->prepare($sql);
	$stmt->execute();
	
	$total_records=$stmt->rowCount();
	
	$rtext=  sprintf(ngettext('%s record', '%s records', $total_records), number($total_records)).' <span class="discreet">'.$rtext.'</span>';


	//$rtext=preg_replace('/\(|\)/', '', $rtext);





	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}



function billingregion_taxcategory($_data, $db, $user, $account) {

	$rtext_label='record';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

	$adata=array();


	if ($result=$db->query($sql)) {

		foreach ($result as $data) {




			switch ($data['Invoice Billing Region']) {
			case 'EU':
				$billing_region=_('European Union');
				break;
			case 'Unknown':
				$billing_region=_('Unknown');
				break;
			case 'NOEU':
				$billing_region=_('Outside European Union');
				break;
			case 'GBIM':
				$billing_region='GB+IM';
				break;
			default:
				$billing_region=$data['Invoice Billing Region'];
				break;
			}





			$adata[]=array(

				'billing_region'=>$billing_region,
				'tax_code'=> sprintf('<span title="%s">%s</span>', ($data['Invoice Tax Code']=='UNK'?_('Unknown tax code'):$data['Tax Category Name']), $data['Invoice Tax Code']),
				'request'=>$data['Invoice Billing Region'].'/'.$data['Invoice Tax Code'],


				'invoices'=>sprintf('<span class="link" onClick="change_view(\'report/billingregion_taxcategory/invoices/%s/%s\')" >%s</span>',
				$data['Invoice Billing Region'],
				$data['Invoice Tax Code'],
				number($data['invoices']))  ,
				
				'refunds'=>sprintf('<span class="link" onClick="change_view(\'report/billingregion_taxcategory/refunds/%s/%s\')" >%s</span>',
				$data['Invoice Billing Region'],
				$data['Invoice Tax Code'],
				number($data['refunds']))  ,

				'customers'=>number($data['customers']) ,
				'tax'=>money($data['tax'], $account->get('Account Currency')) ,
				'net'=>money($data['net'], $account->get('Account Currency')) ,
				'total'=>money($data['total'], $account->get('Account Currency')) ,


			);

		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$rtext=preg_replace('/\(|\)/', '', $rtext);


	if (is_array($parameters['excluded_stores']) and count($parameters['excluded_stores'])>0) {
		$excluded_stores='';
		$sql=sprintf('Select `Store Key`,`Store Code`,`Store Name` from `Store Dimension` where `Store Key` in (%s)', join($parameters['excluded_stores'], ','));

		if ($result=$db->query($sql)) {

			foreach ($result as $data) {
				$excluded_stores.=$data['Store Code'].', ';

			}

		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}

		$excluded_stores=preg_replace('/, $/', '', $excluded_stores);



		$rtext.=' ('._('Excluding').': '.$excluded_stores.')';
	}


	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}


function invoices_billingregion_taxcategory($_data, $db, $user) {

	$rtext_label='invoice';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			if ($data['Invoice Paid']=='Yes')
				$state=_('Paid');
			elseif ($data['Invoice Paid']=='Partially')
				$state=_('Partially Paid');

			else
				$state=_('No Paid');


			if ($data['Invoice Type']=='Invoice')
				$type=_('Invoice');
			elseif ($data['Invoice Type']=='CreditNote')
				$type=_('Credit Note');
			else
				$type=_('Refund');

			switch ($data['Invoice Main Payment Method']) {
			default:
				$method=$data['Invoice Main Payment Method'];
			}

			$adata[]=array(
				'id'=>(integer)$data['Invoice Key'],
				'store_key'=> (integer) $data['Invoice Store Key'],
				'customer_key'=> (integer) $data['Invoice Customer Key'],
				'number'=>$data['Invoice Public ID'],
				'customer'=>$data['Invoice Customer Name'],
				'store_code'=> sprintf('<span title="%s">%s</span>', $data['Store Name'], $data['Store Code']),
				'date'=>strftime("%e %b %Y", strtotime($data['Invoice Date'].' +0:00')),
				'total_amount'=>money($data['Invoice Total Amount'], $data['Invoice Currency']),
				'net'=>money($data['Invoice Total Net Amount'], $data['Invoice Currency']),
				'tax'=>money($data['Invoice Total Tax Amount'], $data['Invoice Currency']),
				'shipping'=>money($data['Invoice Shipping Net Amount'], $data['Invoice Currency']),
				'items'=>money($data['Invoice Items Net Amount'], $data['Invoice Currency']),
				'type'=>$type,
				'method'=>$method,
				'state'=>$state,
				'billing_country'=>$data['Invoice Billing Country 2 Alpha Code'],
				'billing_country_flag'=>sprintf('<img title="%s" src="/art/flags/%s.gif">', $data['Country Name'], strtolower($data['Invoice Billing Country 2 Alpha Code']))

			);

		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	if (is_array($parameters['excluded_stores']) and count($parameters['excluded_stores'])>0) {
		$excluded_stores='';
		$sql=sprintf('Select `Store Key`,`Store Code`,`Store Name` from `Store Dimension` where `Store Key` in (%s)', join($parameters['excluded_stores'], ','));

		if ($result=$db->query($sql)) {
			foreach ($result as $data) {
				$excluded_stores.=$data['Store Code'].', ';
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}

		$excluded_stores=preg_replace('/, $/', '', $excluded_stores);
		$rtext.=' ('._('Excluding').': '.$excluded_stores.')';
	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}


?>
