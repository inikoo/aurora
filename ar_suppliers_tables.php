<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2015 20:13:55 GMT+7, Bangkok Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';


if (!$user->can_view('suppliers')) {
	echo json_encode(array('state'=>405,'resp'=>'Forbidden'));
	exit;
}


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'suppliers':
	suppliers(get_table_parameters(),$db,$user);
	break;

default:
	$response=array('state'=>405,'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function suppliers($_data,$db,$user) {

	global $corporate_currency;

	$rtext_label='supplier';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();


	if ($result=$db->query($sql)) {

		foreach ($result as $data) {
		


		$sales=money($data["Supplier $db_period Acc Parts Sold Amount"],$corporate_currency);

		if (in_array($parameters['f_period'],array('all','3y','three_year'))) {
			$delta_sales='';
		}else {
			$delta_sales='<span title="'.money($data["Supplier $db_period Acc 1YB Parts Sold Amount"],$corporate_currency).'">'.delta($data["Supplier $db_period Acc Parts Sold Amount"],$data["Supplier $db_period Acc 1YB Parts Sold Amount"]).'</span>';
		}

		$profit=money($data["Supplier $db_period Acc Parts Profit"],$corporate_currency);
		$profit_after_storing=money($data["Supplier $db_period Acc Parts Profit After Storing"],$corporate_currency);
		$cost=money($data["Supplier $db_period Acc Parts Cost"],$corporate_currency);
		$margin=percentage($data["Supplier $db_period Acc Parts Margin"],1);
		$sold=number($data["Supplier $db_period Acc Parts Sold"],0);
		$required=number($data["Supplier $db_period Acc Parts Required"],0);


		$adata[]=array(
			'id'=>(integer)$data['Supplier Key'],
			'formatted_id'=>sprintf('%03d',$data['Supplier Key']),
			'name'=>$data['Supplier Name'],
			'low'=>number($data['Supplier Low Availability Products']),
			'high'=>number($data['Supplier Surplus Availability Products']),
			'normal'=>number($data['Supplier Optimal Availability Products']),
			'critical'=>number($data['Supplier Critical Availability Products']),
			'outofstock'=>number($data['Supplier Out Of Stock Products']),
			'location'=>$data['Supplier Location'],
			'email'=>$data['Supplier Main Plain Email'],
			'tel'=>$data['Supplier Main XHTML Telephone'],
			'contact'=>$data['Supplier Main Contact Name'],
			'sold'=>$sold,
			'required'=>$required,
			'origin'=>$data['Supplier Products Origin Country Code'],
			'active_sp'=>number($data['Supplier Active Supplier Products']),
			'no_active_sp'=>number($data['Supplier Discontinued Supplier Products']),

			'delivery_time'=>seconds_to_string(3600*24*$data['Supplier Average Delivery Days']),

			'sales'=>$sales,
			'delta_sales'=>$delta_sales,
			'profit'=>$profit,
			'profit_after_storing'=>$profit_after_storing,
			'cost'=>$cost,
			'pending_pos'=>number($data['Supplier Open Purchase Orders']),
			'margin'=>$margin,
			'sales_year0'=>money($data['Supplier Year To Day Acc Parts Sold Amount'],$corporate_currency),
			'sales_year1'=>money($data['Supplier 1 Year Ago Sales Amount'],$corporate_currency),
			'sales_year2'=>money($data['Supplier 2 Year Ago Sales Amount'],$corporate_currency),
			'sales_year3'=>money($data['Supplier 3 Year Ago Sales Amount'],$corporate_currency),
			'sales_year4'=>money($data['Supplier 4 Year Ago Sales Amount'],$corporate_currency),

			'delta_sales_year0'=>'<span title="'.money($data["Supplier Year To Day Acc 1YB Parts Sold Amount"],$corporate_currency).'">'.delta($data["Supplier Year To Day Acc Parts Sold Amount"],$data["Supplier Year To Day Acc 1YB Parts Sold Amount"]).'</span>',
			'delta_sales_year1'=>'<span title="'.money($data["Supplier 2 Year Ago Sales Amount"],$corporate_currency).'">'.delta($data["Supplier 1 Year Ago Sales Amount"],$data["Supplier 2 Year Ago Sales Amount"]).'</span>',
			'delta_sales_year2'=>'<span title="'.money($data["Supplier 3 Year Ago Sales Amount"],$corporate_currency).'">'.delta($data["Supplier 2 Year Ago Sales Amount"],$data["Supplier 3 Year Ago Sales Amount"]).'</span>',
			'delta_sales_year3'=>'<span title="'.money($data["Supplier 4 Year Ago Sales Amount"],$corporate_currency).'">'.delta($data["Supplier 3 Year Ago Sales Amount"],$data["Supplier 4 Year Ago Sales Amount"]).'</span>'

		);

	
		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
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
