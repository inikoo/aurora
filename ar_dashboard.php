<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2015 at 21:45:48 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
include_once 'utils/date_functions.php';




if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'sales_overview':
	$data=prepare_values($_REQUEST, array(
			'type'=>array('type'=>'string'),
			'period'=>array('type'=>'period'),
			'currency'=>array('type'=>'currency'),


		));
	sales_overview($data, $db, $user, $account);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}



function sales_overview($_data, $db, $user, $account) {


	$_SESSION['dashboard_state']['sales_overview']=array(
		'type'=>$_data['type'],
		'period'=>$_data['period'],
		'currency'=>$_data['currency'],

	);



	
	$data=array();

	if ($_data['type']=='invoices' or $_data['type']=='delivery_notes') {


		$currency=$_data['currency'];

		$period_tag=get_interval_db_name($_data['period']);
		$fields="`Store Code`,S.`Store Key`,`Store Name`, `Store Currency Code` currency, `Store $period_tag Acc Invoices` as invoices,`Store $period_tag Acc Refunds` as refunds,`Store $period_tag Acc Delivery Notes` delivery_notes,`Store $period_tag Acc Replacements` replacements,`Store $period_tag Acc Invoiced Amount` as sales,`Store DC $period_tag Acc Invoiced Amount` as dc_sales,";


	if (!($period_tag=='3 Year' or $period_tag=='Total')) {
		$fields.="`Store $period_tag Acc 1YB Refunds` as refunds_1yb,`Store $period_tag Acc 1YB Delivery Notes` delivery_notes_1yb,`Store $period_tag Acc 1YB Replacements` replacements_1yb, `Store $period_tag Acc 1YB Invoices` as invoices_1yb,`Store $period_tag Acc 1YB Invoiced Amount` as sales_1yb,`Store DC $period_tag Acc 1YB Invoiced Amount` as dc_sales_1yb";

	}else {
		$fields.='0 as refunds_1yb, 0 as replacements_1yb,0 as delivery_notes_1yb, 0 as invoices_1yb, 0 as sales_1yb, 0 as dc_sales_1yb';
	}

	$sql=sprintf("select  %s from `Store Dimension` S left join `Store Data Dimension` SD on (S.`Store Key`=SD.`Store Key`)left join `Store Default Currency` DC on (S.`Store Key`=DC.`Store Key`)", $fields);


		$sum_invoices=0;
		$sum_refunds=0;
		
		$sum_invoices_1yb=0;
		$sum_dc_sales=0;
		$sum_dc_sales_1yb=0;

		$sum_refunds_1yb=0;

        $sum_delivery_notes=0;
		$sum_delivery_notes_1yb=0;
		$sum_replacements=0;
		$sum_replacements_1yb=0;


		if ($result=$db->query($sql)) {

			foreach ($result as $row) {


				$sum_invoices+=$row['invoices'];
				$sum_refunds+=$row['refunds'];
				$sum_replacements+=$row['replacements'];
				$sum_delivery_notes+=$row['delivery_notes'];
				$sum_dc_sales+=$row['dc_sales'];


				$sum_refunds_1yb+=$row['refunds_1yb'];
				$sum_replacements_1yb+=$row['replacements_1yb'];
				$sum_delivery_notes_1yb+=$row['delivery_notes_1yb'];
				$sum_invoices_1yb+=$row['invoices_1yb'];
				$sum_dc_sales_1yb+=$row['dc_sales_1yb'];

				if ($_data['currency']=='store') {
					$data['orders_overview_sales_'.$row['Store Key']]=array('value'=>money($row['sales'], $row['currency']));
					$data['orders_overview_sales_delta_'.$row['Store Key']]=array('value'=>delta($row['sales'], $row['sales_1yb']));
				}else {
					$data['orders_overview_sales_'.$row['Store Key']]=array('value'=>money($row['dc_sales'], $account->get('Account Currency')));
					$data['orders_overview_sales_delta_'.$row['Store Key']]=array('value'=>delta($row['dc_sales'], $row['dc_sales_1yb']));
				}



				$data['orders_overview_invoices_'.$row['Store Key']]=array('value'=>number($row['invoices']));
				$data['orders_overview_refunds_'.$row['Store Key']]=array('value'=>number($row['refunds']));
				$data['orders_overview_replacements_'.$row['Store Key']]=array('value'=>number($row['replacements']));
				$data['orders_overview_delivery_notes_'.$row['Store Key']]=array('value'=>number($row['delivery_notes']));
				$data['orders_overview_delivery_notes_delta_'.$row['Store Key']]=array('value'=>number($row['delivery_notes']));
				
				$data['orders_overview_invoices_delta_'.$row['Store Key']]=array('value'=>delta($row['invoices'], $row['invoices_1yb']));
				$data['orders_overview_refunds_delta_'.$row['Store Key']]=array('value'=>delta($row['refunds'], $row['refunds_1yb']));
				$data['orders_overview_replacements_delta_'.$row['Store Key']]=array('value'=>delta($row['replacements'], $row['replacements_1yb']));
				$data['orders_overview_replacements_percentage_'.$row['Store Key']]=array('value'=>percentage($row['replacements'], $row['delivery_notes']));




			}

		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		$data['orders_overview_invoices_store_totals']=array('value'=>number($sum_invoices));
		$data['orders_overview_refunds_store_totals']=array('value'=>number($sum_refunds));
		$data['orders_overview_invoices_delta_store_totals']=array('value'=>delta($sum_invoices, $sum_invoices_1yb));
		$data['orders_overview_refunds_delta_store_totals']=array('value'=>delta($sum_refunds, $sum_refunds_1yb));


$data['orders_overview_delivery_notes_store_totals']=array('value'=>number($sum_delivery_notes));
		$data['orders_overview_delivery_notes_delta_store_totals']=array('value'=>delta($sum_delivery_notes, $sum_delivery_notes_1yb));
		$data['orders_overview_replacements_store_totals']=array('value'=>number($sum_replacements));
		$data['orders_overview_replacements_delta_store_totals']=array('value'=>delta($sum_replacements, $sum_replacements_1yb));
		$data['orders_overview_replacements_percentage_store_totals']=array('value'=>percentage($sum_replacements, $sum_delivery_notes));
		
		$data['orders_overview_sales_store_totals']=($currency=='store'?array('value'=>''):array('value'=>money($sum_dc_sales, $account->get('Account Currency'))));
		$data['orders_overview_sales_delta_store_totals']=($currency=='store'?array('value'=>''):array('value'=>delta($sum_dc_sales, $sum_dc_sales_1yb)));








	}


	$response=
		array(
		'state'=>200,
		'data'=>$data,
	);

	echo json_encode($response);
}


?>
