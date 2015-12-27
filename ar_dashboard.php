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

	$currency=$_data['currency'];
	$period_tag=get_interval_db_name($_data['period']);

	$data=array();

	if ($_data['type']=='invoice_categories' ) {
	$request='invoice_categories';

		$fields="
		`Invoice Category $period_tag Acc Refunds` as refunds,
		`Invoice Category $period_tag Acc Invoices` as invoices,
		`Invoice Category $period_tag Acc Invoiced Amount` as sales,
		 `Invoice Category DC $period_tag Acc Invoiced Amount` as dc_sales,
     0 delivery_notes,
        0 delivery_notes_1yb,

        0 replacements,
        0 replacements_1yb,
                        ";



		if ($period_tag=='3 Year' or $period_tag=='All') {

			$fields.="
	    0 as refunds_1yb,

	    0 as invoices_1yb,
	    0 as sales_1yb,
        0 as dc_sales_1yb
                        ";
		}
		else {
			$fields.="
		`Invoice Category $period_tag Acc 1YB Refunds` as refunds_1yb,

	    `Invoice Category $period_tag Acc 1YB Invoices` as invoices_1yb,
	    `Invoice Category $period_tag Acc 1YB Invoiced Amount` as sales_1yb,
        `Invoice Category DC $period_tag Acc 1YB Invoiced Amount` as dc_sales_1yb
                        ";

		}
		$sql="select  concat('cat',C.`Category Key`) record_key, `Category Store Key`,`Store Currency Code` currency, $fields from `Invoice Category Dimension` IC left join `Category Dimension` C on (C.`Category Key`=IC.`Invoice Category Key`) left join `Store Dimension` S on (S.`Store Key`=C.`Category Store Key`) order by C.`Category Store Key` ,`Category Function Order`";



	}
	else {
$request='invoices';
		$fields="`Store Code`,S.`Store Key` record_key ,`Store Name`, `Store Currency Code` currency, `Store $period_tag Acc Invoices` as invoices,`Store $period_tag Acc Refunds` as refunds,`Store $period_tag Acc Delivery Notes` delivery_notes,`Store $period_tag Acc Replacements` replacements,`Store $period_tag Acc Invoiced Amount` as sales,`Store DC $period_tag Acc Invoiced Amount` as dc_sales,";


		if (!($period_tag=='3 Year' or $period_tag=='Total')) {
			$fields.="`Store $period_tag Acc 1YB Refunds` as refunds_1yb,`Store $period_tag Acc 1YB Delivery Notes` delivery_notes_1yb,`Store $period_tag Acc 1YB Replacements` replacements_1yb, `Store $period_tag Acc 1YB Invoices` as invoices_1yb,`Store $period_tag Acc 1YB Invoiced Amount` as sales_1yb,`Store DC $period_tag Acc 1YB Invoiced Amount` as dc_sales_1yb";

		}else {
			$fields.='0 as refunds_1yb, 0 as replacements_1yb,0 as delivery_notes_1yb, 0 as invoices_1yb, 0 as sales_1yb, 0 as dc_sales_1yb';
		}

		$sql=sprintf("select  %s from `Store Dimension` S left join `Store Data Dimension` SD on (S.`Store Key`=SD.`Store Key`)left join `Store Default Currency` DC on (S.`Store Key`=DC.`Store Key`)", $fields);

	}

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
				$data['orders_overview_sales_'.$row['record_key']]=array('value'=>money($row['sales'], $row['currency']));
				$data['orders_overview_sales_delta_'.$row['record_key']]=array('value'=>delta($row['sales'], $row['sales_1yb']) , 'title'=>money($row['sales_1yb'], $row['currency'])  );
			}else {
				$data['orders_overview_sales_'.$row['record_key']]=array('value'=>money($row['dc_sales'], $account->get('Account Currency')));
				$data['orders_overview_sales_delta_'.$row['record_key']]=array('value'=>delta($row['dc_sales'], $row['dc_sales_1yb']) , 'title'=>money($row['dc_sales_1yb'], $account->get('Account Currency'))  );
			}



			$data['orders_overview_invoices_'.$row['record_key']]=array('value'=>number($row['invoices']),'request'=>"$request/".$row['record_key']  );
			$data['orders_overview_invoices_delta_'.$row['record_key']]=array('value'=>delta($row['invoices'], $row['invoices_1yb']), 'title'=>number($row['invoices_1yb'])  );


			$data['orders_overview_delivery_notes_'.$row['record_key']]=array('value'=>number($row['delivery_notes']));
			$data['orders_overview_delivery_notes_delta_'.$row['record_key']]=array('value'=>delta($row['delivery_notes'], $row['delivery_notes_1yb']), 'title'=>number($row['delivery_notes_1yb']));


			$data['orders_overview_refunds_'.$row['record_key']]=array('value'=>number($row['refunds']));
			$data['orders_overview_refunds_delta_'.$row['record_key']]=array('value'=>delta($row['refunds'], $row['refunds_1yb']), 'title'=>number($row['refunds_1yb']) );



			$data['orders_overview_replacements_'.$row['record_key']]=array('value'=>number($row['replacements']));
			$data['orders_overview_replacements_delta_'.$row['record_key']]=array('value'=>delta($row['replacements'], $row['replacements_1yb']), 'title'=>number($row['replacements_1yb']) );
			$data['orders_overview_replacements_percentage_'.$row['record_key']]=array('value'=>percentage($row['replacements'], $row['delivery_notes']));
			$data['orders_overview_replacements_percentage_1yb_'.$row['record_key']]=array('value'=>percentage($row['replacements_1yb'], $row['delivery_notes_1yb']), 'title'=>number($row['replacements_1yb']).'/'.number( $row['delivery_notes_1yb']));
			


		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	$data['orders_overview_sales_totals']=($currency=='store'?array('value'=>''):array('value'=>money($sum_dc_sales, $account->get('Account Currency'))));
	$data['orders_overview_sales_delta_totals']=($currency=='store'?array('value'=>''):array('value'=>delta($sum_dc_sales, $sum_dc_sales_1yb) , 'title'=>money($sum_dc_sales_1yb, $account->get('Account Currency'))  )  );


	$data['orders_overview_invoices_totals']=array('value'=>number($sum_invoices));
	$data['orders_overview_invoices_delta_totals']=array('value'=>delta($sum_invoices, $sum_invoices_1yb), 'title'=>number($sum_invoices_1yb));


	$data['orders_overview_refunds_totals']=array('value'=>number($sum_refunds));
	$data['orders_overview_refunds_delta_totals']=array('value'=>delta($sum_refunds, $sum_refunds_1yb), 'title'=>number($sum_refunds_1yb));


	$data['orders_overview_delivery_notes_totals']=array('value'=>number($sum_delivery_notes));
	$data['orders_overview_delivery_notes_delta_totals']=array('value'=>delta($sum_delivery_notes, $sum_delivery_notes_1yb), 'title'=>number($sum_delivery_notes_1yb));

	$data['orders_overview_replacements_totals']=array('value'=>number($sum_replacements));
	$data['orders_overview_replacements_delta_totals']=array('value'=>delta($sum_replacements, $sum_replacements_1yb), 'title'=>number($sum_replacements_1yb));
	$data['orders_overview_replacements_percentage_totals']=array('value'=>percentage($sum_replacements, $sum_delivery_notes));
	$data['orders_overview_replacements_percentage_1yb_totals']=array('value'=>percentage($sum_replacements_1yb, $sum_delivery_notes_1yb), 'title'=>number($sum_replacements_1yb).'/'.number($sum_delivery_notes_1yb));

	
	$response=
		array(
		'state'=>200,
		'data'=>$data,
	);

	echo json_encode($response);
}


?>
