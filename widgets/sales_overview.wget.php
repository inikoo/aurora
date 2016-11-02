<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2015 at 16:59:46 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_dashbord_sales_overview($db, $account, $user, $smarty, $type, $period, $currency, $orders_view_type) {

	include_once 'utils/date_functions.php';


	$smarty->assign('type', $type);
	$smarty->assign('currency', $currency);
	$smarty->assign('orders_view_type', $orders_view_type);
	$smarty->assign('period', $period);



	$sales_overview=array();
	$period_tag=get_interval_db_name($period);

	$adata=array();


	// Pending Orders ---------------




	//-----------------



	$sum_invoices=0;
	$sum_delivery_notes=0;
	$sum_refunds=0;
	$sum_invoices_1yb=0;
	$sum_dc_sales=0;
	$sum_dc_sales_1yb=0;
	$sum_refunds_1yb=0;
	$sum_replacements=0;
	$sum_replacements_1yb=0;
	$sum_delivery_notes_1yb=0;

	$sum_in_basket=0;
	$sum_in_basket_amount=0;
	$sum_in_process_paid=0;
	$sum_in_process_amount_paid=0;
	$sum_in_process_not_paid=0;
	$sum_in_process_amount_not_paid=0;
	$sum_in_warehouse=0;
	$sum_in_warehouse_amount=0;
	$sum_packed=0;
	$sum_packed_amount=0;
	$sum_in_dispatch_area=0;
	$sum_in_dispatch_area_amount=0;

	$fields="
	`Store Orders In Basket Number`,`Store Orders In Basket Amount`,`Store DC Orders In Basket Amount`,
	`Store Orders In Process Paid Number`,`Store Orders In Process Paid Amount`,`Store DC Orders In Process Paid Amount`,
	`Store Orders In Process Not Paid Number`,`Store Orders In Process Not Paid Amount`,`Store DC Orders In Process Not Paid Amount`,

	`Store Orders In Warehouse Number`,`Store Orders In Warehouse Amount`,`Store DC Orders In Warehouse Amount`,
	`Store Orders Packed Number`,`Store Orders Packed Amount`,`Store DC Orders Packed Amount`,
	`Store Orders In Dispatch Area Number`,`Store Orders In Dispatch Area Amount`,`Store DC Orders In Dispatch Area Amount`,

	`Store Code`,S.`Store Key`,`Store Name`, `Store Currency Code` currency, `Store $period_tag Acc Invoices` as invoices,`Store $period_tag Acc Refunds` as refunds,`Store $period_tag Acc Delivery Notes` delivery_notes,`Store $period_tag Acc Replacements` replacements,`Store $period_tag Acc Invoiced Amount` as sales,`Store DC $period_tag Acc Invoiced Amount` as dc_sales,";
	if (!($period_tag=='3 Year' or $period_tag=='Total')) {
		$fields.="`Store $period_tag Acc 1YB Refunds` as refunds_1yb,`Store $period_tag Acc 1YB Delivery Notes` delivery_notes_1yb,`Store $period_tag Acc 1YB Replacements` replacements_1yb, `Store $period_tag Acc 1YB Invoices` as invoices_1yb,`Store $period_tag Acc 1YB Invoiced Amount` as sales_1yb,`Store DC $period_tag Acc 1YB Invoiced Amount` as dc_sales_1yb";

	}else {
		$fields.='0 as refunds_1yb, 0 as replacements_1yb,0 as delivery_notes_1yb, 0 as invoices_1yb, 0 as sales_1yb, 0 as dc_sales_1yb';
	}

	$sql=sprintf("select  %s from `Store Dimension` S left join `Store Data` SD on (S.`Store Key`=SD.`Store Key`) left join `Store DC Data` DC on (S.`Store Key`=DC.`Store Key`)", $fields);


	if ($result=$db->query($sql)) {

		foreach ($result as $row) {

			if ($type!='invoice_categories') {
				$sum_invoices+=$row['invoices'];
				$sum_delivery_notes+=$row['delivery_notes'];

				$sum_refunds+=$row['refunds'];
				$sum_refunds_1yb+=$row['refunds_1yb'];
				$sum_replacements+=$row['replacements'];
				$sum_replacements_1yb+=$row['replacements_1yb'];
				$sum_invoices_1yb+=$row['invoices_1yb'];

				$sum_delivery_notes_1yb+=$row['delivery_notes_1yb'];

				$sum_dc_sales+=$row['dc_sales'];
				$sum_dc_sales_1yb+=$row['dc_sales_1yb'];

				$sum_in_basket+=$row['Store Orders In Basket Number'];
				$sum_in_basket_amount+=$row['Store Orders In Basket Amount'];
				$sum_in_process_paid+=$row['Store Orders In Process Paid Number'];
				$sum_in_process_amount_paid+=$row['Store Orders In Process Paid Amount'];
				$sum_in_process_not_paid+=$row['Store Orders In Process Not Paid Number'];
				$sum_in_process_amount_not_paid+=$row['Store Orders In Process Not Paid Amount'];
				$sum_in_warehouse+=$row['Store Orders In Warehouse Number'];
				$sum_in_warehouse_amount+=$row['Store Orders In Warehouse Amount'];
				$sum_in_warehouse+=$row['Store Orders Packed Number'];
				$sum_in_warehouse_amount+=$row['Store Orders Packed Amount'];
				$sum_in_dispatch_area+=$row['Store Orders In Dispatch Area Number'];
				$sum_in_dispatch_area_amount+=$row['Store Orders In Dispatch Area Amount'];


			}



			$sales_overview[]=array(
				'class'=>'record store '.($type=='invoice_categories'?'hide':''),
				'id'=>$row['Store Key'],
				'label'=>array('label'=>$row['Store Name'], 'title'=>$row['Store Code'], 'view'=>'store/'.$row['Store Key']),

				'in_basket'=>array('value'=>number($row['Store Orders In Basket Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
				'in_basket_amount'=>array('value'=>($currency=='store'?money($row['Store Orders In Basket Amount'], $row['currency']):money($row['Store DC Orders In Basket Amount'], $account->get('Account Currency'))))  ,
				'in_process_paid'=>array('value'=>number($row['Store Orders In Process Paid Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
				'in_process_amount_paid'=>array('value'=>($currency=='store'?money($row['Store Orders In Process Paid Amount'], $row['currency']):money($row['Store DC Orders In Process Paid Amount'], $account->get('Account Currency'))))  ,
				'in_process_not_paid'=>array('value'=>number($row['Store Orders In Process Not Paid Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
				'in_process_amount_not_paid'=>array('value'=>($currency=='store'?money($row['Store Orders In Process Not Paid Amount'], $row['currency']):money($row['Store DC Orders In Process Not Paid Amount'], $account->get('Account Currency'))))  ,
				'in_warehouse'=>array('value'=>number($row['Store Orders In Warehouse Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
				'in_warehouse_amount'=>array('value'=>($currency=='store'?money($row['Store Orders In Warehouse Amount'], $row['currency']):money($row['Store DC Orders In Warehouse Amount'], $account->get('Account Currency'))))  ,
				'packed'=>array('value'=>number($row['Store Orders Packed Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
				'packed_amount'=>array('value'=>($currency=='store'?money($row['Store Orders Packed Amount'], $row['currency']):money($row['Store DC Orders Packed Amount'], $account->get('Account Currency'))))  ,
				'in_dispatch_area'=>array('value'=>number($row['Store Orders In Dispatch Area Number']), 'title'=>'', 'view'=>'store/'.$row['Store Key']),
				'in_dispatch_area_amount'=>array('value'=>($currency=='store'?money($row['Store Orders In Dispatch Area Amount'], $row['currency']):money($row['Store DC Orders In Dispatch Area Amount'], $account->get('Account Currency'))))  ,

				'invoices'=>array('value'=>number($row['invoices']), 'view'=>'invoices/'.$row['Store Key']),
				'invoices_1yb'=>number($row['invoices_1yb']),
				'invoices_delta'=>delta($row['invoices'], $row['invoices_1yb']).' '.delta_icon($row['invoices'], $row['invoices_1yb']),

				'delivery_notes'=>number($row['delivery_notes']),
				'delivery_notes_1yb'=>number($row['delivery_notes_1yb']),
				'delivery_notes_delta'=>delta($row['delivery_notes'], $row['delivery_notes_1yb']).' '.delta_icon($row['delivery_notes'], $row['delivery_notes_1yb']),

				'refunds'=>array('value'=>number($row['refunds']), 'view'=>'invoices/'.$row['Store Key']),

				'refunds_1yb'=>number($row['refunds_1yb']),
				'refunds_delta'=>delta($row['refunds'], $row['refunds_1yb']).' '.delta_icon($row['refunds'], $row['refunds_1yb']),

				'replacements'=>number($row['replacements']),
				'replacements_percentage'=>percentage($row['replacements'], $row['delivery_notes']),
				'replacements_delta'=>delta($row['replacements'], $row['replacements_1yb']).' '.delta_icon($row['replacements'], $row['replacements_1yb']),
				'replacements_percentage_1yb'=>percentage($row['replacements_1yb'], $row['delivery_notes_1yb']),
				'replacements_1yb'=>number( $row['delivery_notes_1yb']),



				'sales'=>($currency=='store'?money($row['sales'], $row['currency']):money($row['dc_sales'], $account->get('Account Currency')))  ,
				'sales_1yb'=>($currency=='store'?money($row['sales_1yb'], $row['currency']):money($row['dc_sales_1yb'], $account->get('Account Currency')))  ,
				'sales_delta'=>delta($row['sales'], $row['sales_1yb']).' '.delta_icon($row['sales'], $row['sales_1yb'])


			);
			//print_r($row);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
		exit;
	}


	/*-------- Invoice Categories*/




	$fields="`Invoice Category $period_tag Acc Refunds` as refunds,`Invoice Category $period_tag Acc Invoices` as invoices,`Invoice Category $period_tag Acc Amount` as sales,`Invoice Category DC $period_tag Acc Amount` as dc_sales, 0 delivery_notes,
        0 delivery_notes_1yb,
        0 replacements,
        0 replacements_1yb,
                        ";


	if ($period_tag=='3 Year' or $period_tag=='Total') {

		$fields.="
	    0 as refunds_1yb,

	    0 as invoices_1yb,
	    0 as sales_1yb,
        0 as dc_sales_1yb
                        ";
	}else {
		$fields.="
		`Invoice Category $period_tag Acc 1YB Refunds` as refunds_1yb,
	    `Invoice Category $period_tag Acc 1YB Invoices` as invoices_1yb,
	    `Invoice Category $period_tag Acc 1YB Amount` as sales_1yb,
        `Invoice Category DC $period_tag Acc 1YB Amount` as dc_sales_1yb";

	}



	$sql="select  C.`Category Key`,`Category Label`, `Category Store Key`,`Store Currency Code` currency, $fields from `Invoice Category Dimension` IC  left join `Invoice Category Data` ICD on (IC.`Invoice Category Key`=ICD.`Invoice Category Key`)  left join `Invoice Category DC Data` ICSCD on (IC.`Invoice Category Key`=ICSCD.`Invoice Category Key`)   left join `Category Dimension` C on (C.`Category Key`=IC.`Invoice Category Key`) left join `Store Dimension` S on (S.`Store Key`=C.`Category Store Key`) order by C.`Category Store Key` ,`Category Function Order`";

	if ($result=$db->query($sql)) {

		foreach ($result as $row) {

			if ($type=='invoice_categories') {
				$sum_invoices+=$row['invoices'];
				$sum_delivery_notes+=$row['delivery_notes'];

				$sum_refunds+=$row['refunds'];
				$sum_refunds_1yb+=$row['refunds_1yb'];
				$sum_replacements+=$row['replacements'];
				$sum_replacements_1yb+=$row['replacements_1yb'];
				$sum_invoices_1yb+=$row['invoices_1yb'];

				$sum_delivery_notes_1yb+=$row['delivery_notes_1yb'];

				$sum_dc_sales+=$row['dc_sales'];
				$sum_dc_sales_1yb+=$row['dc_sales_1yb'];
			}


			$sales_overview[]=array(
				'class'=>'record category '.($type!='invoice_categories'?'hide':''),
				'id'=>'cat'.$row['Category Key'],


				'label'=>array('label'=>$row['Category Label'], 'title'=>$row['Category Label'], 'view'=>'invoices/all/category/'.$row['Category Key']),


				'invoices'=>array('value'=>number($row['invoices']), 'view'=>'invoices/all/category/'.$row['Category Key']),

				'invoices_1yb'=>number($row['invoices_1yb']),
				'invoices_delta'=>delta($row['invoices'], $row['invoices_1yb']).' '.delta_icon($row['invoices'], $row['invoices_1yb']),

				'delivery_notes'=>number($row['delivery_notes']),
				'delivery_notes_1yb'=>number($row['delivery_notes_1yb']),
				'delivery_notes_delta'=>delta($row['delivery_notes'], $row['delivery_notes_1yb']).' '.delta_icon($row['delivery_notes'], $row['delivery_notes_1yb']),

				'refunds'=>array('value'=>number($row['refunds']), 'view'=>'invoices/all/category/'.$row['Category Key']),

				'refunds_1yb'=>number($row['refunds_1yb']),
				'refunds_delta'=>delta($row['refunds'], $row['refunds_1yb']).' '.delta_icon($row['refunds'], $row['refunds_1yb']),

				'replacements'=>number($row['replacements']),
				'replacements_percentage'=>percentage($row['replacements'], $row['delivery_notes']),
				'replacements_delta'=>delta($row['replacements'], $row['replacements_1yb']).' '.delta_icon($row['replacements'], $row['replacements_1yb']),
				'replacements_percentage_1yb'=>percentage($row['replacements_1yb'], $row['delivery_notes_1yb']),
				'replacements_1yb'=>number( $row['delivery_notes_1yb']),



				'sales'=>($currency=='store'?money($row['sales'], $row['currency']):money($row['dc_sales'], $account->get('Account Currency')))  ,
				'sales_1yb'=>($currency=='store'?money($row['sales_1yb'], $row['currency']):money($row['dc_sales_1yb'], $account->get('Account Currency')))  ,
				'sales_delta'=>delta($row['sales'], $row['sales_1yb']).' '.delta_icon($row['sales'], $row['sales_1yb'])


			);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
		exit;
	}




	/*---------*/


	$sales_overview[]=array(
		'id'=>'totals',
		'class'=>'totals',
		'label'=>array('label'=>_('Total')),
		'in_basket'=>array('value'=>number($sum_in_basket)),
		'in_basket_amount'=>array('value'=>($currency=='store'?'':money($sum_in_basket_amount, $account->get('Account Currency'))) ),

		'in_process_paid'=>array('value'=>number($sum_in_process_paid)),
		'in_process_amount_paid'=>array('value'=>($currency=='store'?'':money($sum_in_process_amount_paid, $account->get('Account Currency'))) ),
		'in_process_not_paid'=>array('value'=>number($sum_in_process_not_paid)),
		'in_process_amount_not_paid'=>array('value'=>($currency=='store'?'':money($sum_in_process_amount_not_paid, $account->get('Account Currency'))) ),

		'in_warehouse'=>array('value'=>number($sum_in_warehouse)),
		'in_warehouse_amount'=>array('value'=>($currency=='store'?'':money($sum_in_warehouse_amount, $account->get('Account Currency'))) ),
		'packed'=>array('value'=>number($sum_packed)),
		'packed_amount'=>array('value'=>($currency=='store'?'':money($sum_packed_amount, $account->get('Account Currency'))) ),


		'in_dispatch_area'=>array('value'=>number($sum_in_dispatch_area)),
		'in_dispatch_area_amount'=>array('value'=>($currency=='store'?'':money($sum_in_dispatch_area_amount, $account->get('Account Currency'))) ),

		'invoices'=>array('value'=>number($sum_invoices)),
		'invoices_1yb'=>number($sum_invoices_1yb),
		'invoices_delta'=>delta($sum_invoices, $sum_invoices_1yb).' '.delta_icon($sum_invoices, $sum_invoices_1yb),

		'delivery_notes'=>number($sum_delivery_notes),
		'delivery_notes_1yb'=>number($sum_delivery_notes_1yb),
		'delivery_notes_delta'=>delta($sum_delivery_notes, $sum_delivery_notes_1yb).' '.delta_icon($sum_delivery_notes, $sum_delivery_notes_1yb),

		'refunds'=>array('value'=>number($sum_refunds)),

		'refunds_1yb'=>number($sum_refunds_1yb),
		'refunds_delta'=>delta($sum_refunds, $sum_refunds_1yb).' '.delta_icon($sum_refunds, $sum_refunds_1yb),

		'replacements'=>number($sum_replacements),
		'replacements_percentage'=>percentage($sum_replacements, $sum_delivery_notes),
		'replacements_delta'=>delta($sum_replacements, $sum_replacements_1yb).' '.delta_icon($sum_replacements, $sum_replacements_1yb),
		'replacements_percentage_1yb'=>percentage($sum_replacements_1yb, $sum_delivery_notes_1yb),
		'replacements_1yb'=>number($sum_replacements_1yb),

		'sales'=>($currency=='store'?'':money($sum_dc_sales, $account->get('Account Currency')))  ,
		'sales_1yb'=>($currency=='store'?'':money($sum_dc_sales_1yb, $account->get('Account Currency')))  ,
		'sales_delta'=>($currency=='store'?'':delta($sum_dc_sales, $sum_dc_sales_1yb)).' '.delta_icon($sum_dc_sales, $sum_dc_sales_1yb)

	);

	// print_r($sales_overview);


	$smarty->assign('sales_overview', $sales_overview);

	return $smarty->fetch('dashboard/sales_overview.dbard.tpl');
}


?>
