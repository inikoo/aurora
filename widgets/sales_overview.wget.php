<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2015 at 16:59:46 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_dashbord_sales_overview($db,$account,$user,$smarty,$type,$period,$currency) {

	include_once 'utils/date_functions.php';


	
	$smarty->assign('type', $type);
	$smarty->assign('currency', $currency);
	$smarty->assign('period', $period);


	$sales_overview=array();
	$period_tag=get_interval_db_name($period);
	$fields="`Store Code`,S.`Store Key`,`Store Name`, `Store Currency Code` currency, `Store $period_tag Acc Invoices` as invoices,`Store $period_tag Acc Refunds` as refunds,`Store $period_tag Acc Delivery Notes` delivery_notes,`Store $period_tag Acc Replacements` replacements,`Store $period_tag Acc Invoiced Amount` as sales,`Store DC $period_tag Acc Invoiced Amount` as dc_sales,";


	if (!($period_tag=='3 Year' or $period_tag=='Total')) {
		$fields.="`Store $period_tag Acc 1YB Refunds` as refunds_1yb,`Store $period_tag Acc 1YB Delivery Notes` delivery_notes_1yb,`Store $period_tag Acc 1YB Replacements` replacements_1yb, `Store $period_tag Acc 1YB Invoices` as invoices_1yb,`Store $period_tag Acc 1YB Invoiced Amount` as sales_1yb,`Store DC $period_tag Acc 1YB Invoiced Amount` as dc_sales_1yb";

	}else {
		$fields.='0 as refunds_1yb, 0 as replacements_1yb,0 as delivery_notes_1yb, 0 as invoices_1yb, 0 as sales_1yb, 0 as dc_sales_1yb';
	}

	$sql=sprintf("select  %s from `Store Dimension` S left join `Store Data Dimension` SD on (S.`Store Key`=SD.`Store Key`)left join `Store Default Currency` DC on (S.`Store Key`=DC.`Store Key`)", $fields);
	$adata=array();


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

	if ($result=$db->query($sql)) {

		foreach ($result as $row) {


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



			$sales_overview[]=array(
				'class'=>'record',
				'id'=>$row['Store Key'],
				'label'=>array('label'=>$row['Store Name'], 'title'=>$row['Store Name'], 'view'=>'store/'.$row['Store Key']),
				'invoices'=>number($row['invoices']),
				'delivery_notes'=>number($row['delivery_notes']),
				'delivery_notes_delta'=>'<span title="'.number($row['delivery_notes_1yb']).'">'.delta($row['delivery_notes'], $row['delivery_notes_1yb']).'</span>',

				'refunds'=>number($row['refunds']),

				'invoices_1yb'=>number($row['invoices_1yb']),
				'invoices_delta'=>'<span title="'.number($row['invoices_1yb']).'">'.delta($row['invoices'], $row['invoices_1yb']).'</span>',
				'refunds_delta'=>delta($row['refunds'], $row['refunds_1yb']),
				
				'replacements'=>number($row['replacements']),
				'replacements_percentage'=>percentage($row['replacements'],$row['delivery_notes']),
				'replacements_delta'=>delta($row['replacements'], $row['replacements_1yb']),
				'replacements_1yb'=>number($row['replacements_1yb']),
				
				
				'invoices_share'=>$row['invoices'],
				'sales'=>($currency=='store'?money($row['sales'], $row['currency']):money($row['dc_sales_1yb'], $account->get('Account Currency')))  ,
				'sales_1yb'=>money($row['sales_1yb'], $row['currency']),
				'sales_delta'=>'<span title="'.money($row['sales_1yb']).'">'.delta($row['sales'], $row['sales_1yb']).'</span>',
				'dc_sales'=>money($row['dc_sales']),
				'dc_sales_1yb_'=>money($row['dc_sales_1yb']),
				'dc_sales_delta'=>'<span title="'.money($row['dc_sales_1yb']).'">'.delta($row['dc_sales'], $row['dc_sales_1yb']).'</span>',
				'dc_sales_share'=>$row['dc_sales']
			);

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	foreach ($sales_overview as $key=>$value) {

		$sales_overview[$key]['invoices_share']=percentage($sales_overview[$key]['invoices_share'], $sum_invoices);
		$sales_overview[$key]['dc_sales_share']=percentage($sales_overview[$key]['dc_sales_share'], $sum_dc_sales);

	}

	$sales_overview[]=array(
		'id'=>'store_totals',
		'class'=>'totals',
		'label'=>array('label'=>_('Total')),
		'invoices'=>number($sum_invoices),
		'delivery_notes'=>number($sum_delivery_notes),
				'delivery_notes_delta'=>'<span title="'.number($sum_delivery_notes_1yb).'">'.delta($sum_delivery_notes, $sum_delivery_notes_1yb).'</span>',

		'refunds'=>number($sum_refunds),
		'replacements'=>number($sum_replacements),
		'refunds_delta'=>delta($row['refunds'], $row['refunds_1yb']),
		
				'replacements'=>number($sum_replacements),
				'replacements_percentage'=>percentage($sum_replacements,$sum_delivery_notes),
				'replacements_delta'=>delta($sum_replacements, $sum_replacements_1yb),
				
				'replacements_1yb'=>number($sum_replacements_1yb),

		'invoices_1yb'=>'',
		'invoices_delta'=>'<span title="'.number($sum_invoices_1yb).'">'.delta($sum_invoices, $sum_invoices_1yb).'</span>',
		'sales'=>($currency=='store'?'':money($sum_dc_sales, $account->get('Account Currency')))  ,
		'sales_delta'=>($currency=='store'?'':delta($sum_dc_sales, $sum_dc_sales_1yb))  ,
		'invoices_share'=>''
	);



	$smarty->assign('sales_overview', $sales_overview);

	return $smarty->fetch('dashboard/sales_overview.dbard.tpl');
}


?>
