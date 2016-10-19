<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 December 2015 at 23:56:43 CET, Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('staff')) {
	echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
	exit;
}


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'suppliers':
	suppliers(get_table_parameters(), $db, $user, $account);
	break;
case 'supplier_parts':
	supplier_parts(get_table_parameters(), $db, $user, $account);
	break;	
case 'materials':
	materials(get_table_parameters(), $db, $user, $account);
	break;		
case 'operatives':
	operatives(get_table_parameters(), $db, $user);
	break;
case 'manufacture_tasks':
	manufacture_tasks(get_table_parameters(), $db, $user, $account);
	break;
	

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function operatives($_data, $db, $user) {
	
	$rtext_label='operative';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			switch ($data['Staff Type']) {
			case 'Employee':
				$type=_('Employee');
				break;
			case 'Volunteer':
				$type=_('Volunteer');
				break;
			case 'TemporalWorker':
				$type=_("Temporal worker");
				break;
			case 'WorkExperience':
				$type=_("Work experience");
				break;
			default:
				$type=$data['Staff Type'];
				break;
			}

			$adata[]=array(
				'id'=>(integer) $data['Staff Key'],
				'formatted_id'=>sprintf("%04d", $data['Staff Key']),
				'payroll_id'=>$data['Staff ID'],
				'name'=>$data['Staff Name'],
				'code'=>$data['Staff Alias'],
				'code_link'=>$data['Staff Alias'],
				'type'=>$type,
				'supervisors'=>$data['supervisors']
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

function manufacture_tasks($_data, $db, $user,$account) {
	
	$rtext_label='manufacture task';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			
			$adata[]=array(
				'id'=>(integer) $data['Manufacture Task Key'],
				'name'=>$data['Manufacture Task Name'],
				'work_cost'=>($data['Manufacture Task Work Cost']!=''?money($data['Manufacture Task Work Cost'],$account->get('Currency')):_('NA')),
				
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

function suppliers($_data, $db, $user, $account) {


	if ($user->get('User Type')=='Agent') {

		if ( !($_data['parameters']['parent']=='agent' and  $_data['parameters']['parent_key']==$user->get('User Parent Key') )  ) {
			echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
			exit;
		}


	}else {


		if (!$user->can_view('suppliers')) {
			echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
			exit;
		}

	}



	$rtext_label='supplier';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();



	if ($result=$db->query($sql)) {

		foreach ($result as $data) {


			if ($_data['parameters']['parent']=='agent') {
				$operations=sprintf('<i agent_key="%d" supplier_key="%d"  class="fa fa-chain-broken button" aria-hidden="true"  onClick="bridge_supplier(this)" ></i>',
					$_data['parameters']['parent_key'],
					$data['Supplier Key']
				);
			}else {
				$operations='';
			}

		

			$associated=sprintf('<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']);

			$adata[]=array(
				'id'=>(integer)$data['Supplier Key'],
				'operations'=>$operations,
				'associated'=>$associated,

				'code'=>$data['Supplier Code'],
				'name'=>$data['Supplier Name'],
				'supplier_parts'=>number($data['Supplier Number Parts']),
				'active_supplier_parts'=>number($data['Supplier Number Active Parts']),

				'surplus'=>sprintf('<span class="%s" title="%s">%s</span>', (ratio($data['Supplier Number Surplus Parts'], $data['Supplier Number Parts'])>.75?'error':(ratio($data['Supplier Number Surplus Parts'], $data['Supplier Number Parts'])>.5?'warning':'')), percentage($data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Surplus Parts'])),
				'optimal'=>sprintf('<span  title="%s">%s</span>', percentage($data['Supplier Number Optimal Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Optimal Parts'])),
				'low'=>sprintf('<span class="%s" title="%s">%s</span>', (ratio($data['Supplier Number Low Parts'], $data['Supplier Number Parts'])>.5?'error':(ratio($data['Supplier Number Low Parts'], $data['Supplier Number Parts'])>.25?'warning':'')), percentage($data['Supplier Number Low Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Low Parts'])),
				'critical'=>sprintf('<span class="%s" title="%s">%s</span>', ($data['Supplier Number Critical Parts']==0?'': (ratio($data['Supplier Number Critical Parts'], $data['Supplier Number Parts'])>.25?'error':'warning')), percentage($data['Supplier Number Critical Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Critical Parts'])),
				'out_of_stock'=>sprintf('<span class="%s" title="%s">%s</span>', ($data['Supplier Number Out Of Stock Parts']==0?'':(ratio($data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts'])>.10?'error':'warning')), percentage($data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']), number($data['Supplier Number Out Of Stock Parts'])),


				'location'=>$data['Supplier Location'],
				'email'=>$data['Supplier Main Plain Email'],
				'telephone'=>$data['Supplier Preferred Contact Number Formatted Number'],
				'contact'=>$data['Supplier Main Contact Name'],
				'company'=>$data['Supplier Company Name'],
				
				

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

function supplier_parts($_data, $db, $user, $account) {



	include_once 'utils/currency_functions.php';

	if ($user->get('User Type')=='Agent') {
		// $_data['parameters']['parent']=='supplier' and $_data['parameters']['parent_key']==$user->get('User Parent Key')
		if (!$_data['parameters']['parent']=='supplier') {
			echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
			exit;
		}else {
			$sql=sprintf('select count(*) as num from `Agent Supplier Bridge` where `Agent Supplier Agent Key`=%d and `Agent Supplier Supplier Key`=%d ',
				$user->get('User Parent Key'),
				$_data['parameters']['parent_key']
			);

			$ok=0;
			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$ok=$row['num'];
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}
			if ($ok==0) {
				echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
				exit;
			}

		}


	}elseif (!$user->can_view('suppliers')) {
		echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
		exit;
	}



	$rtext_label='production part';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();


	$exchange=-1;


	if ($result=$db->query($sql)) {



		foreach ($result as $data) {


			if ($exchange<0) {
				$exchange=currency_conversion($db, $data['Supplier Part Currency Code'], $account->get('Account Currency'), '- 1 day');
			}

			if ($exchange!=1) {

				$exchange_info=money(($data['Supplier Part Unit Cost']+$data['Supplier Part Unit Extra Cost']), $data['Supplier Part Currency Code']).' @'.$data['Supplier Part Currency Code'].'/'. $account->get('Account Currency').' '.sprintf('%.6f', $exchange);
			}else {
				$exchange_info='';
			}

			switch ($data['Supplier Part Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-stop success" title="%s"></i>', _('Available'));
				break;
			case 'NoAvailable':
				$status=sprintf('<i class="fa fa-stop warning" title="%s"></i>', _('No available'));

				break;
			case 'Discontinued':
				$status=sprintf('<i class="fa fa-ban error" title="%s"></i>', _('Discontinued'));

				break;
			default:
				$status=$data['Supplier Part Status'];
				break;
			}

			switch ($data['Part Stock Status']) {
			case 'Surplus':
				$stock_status='<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Optimal':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Low':
				$stock_status='<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Critical':
				$stock_status='<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Out_Of_Stock':
				$stock_status='<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
				break;
			case 'Error':
				$stock_status='<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
				break;
			default:
				$stock_status=$data['Part Stock Status'];
				break;
			}

			if ($data['Part Status']=='Not In Use') {
				$part_status='<i class="fa fa-square-o fa-fw  very_discreet" aria-hidden="true"></i> ';

			}elseif ($data['Part Status']=='Discontinuing') {
				$part_status='<i class="fa fa-square fa-fw  very_discreet" aria-hidden="true"></i> ';

			}else {
				$part_status='<i class="fa fa-square fa-fw " aria-hidden="true"></i> ';
			}

			$part_description=$part_status.'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> ';

			$adata[]=array(
				'id'=>(integer)$data['Supplier Part Key'],
				'supplier_key'=>(integer)$data['Supplier Part Supplier Key'],
				'supplier_code'=>$data['Supplier Code'],
				'part_key'=>(integer)$data['Supplier Part Part SKU'],
				'part_reference'=>$data['Part Reference'],
				'reference'=>$data['Supplier Part Reference'],
				'part_description'=>$part_description,


				'description'=>$data['Part Unit Description'],
				'status'=>$status,
				'cost'=>money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
				'delivered_cost'=>'<span title="'.$exchange_info.'">'.money($exchange*($data['Supplier Part Unit Cost']+$data['Supplier Part Unit Extra Cost']), $account->get('Account Currency')).'</span>',
				'packing'=>'
				 <div style="float:right;min-width:30px;;text-align:right" title="'._('Units per carton').'"><span class="discreet" >'.($data['Part Units Per Package']*$data['Supplier Part Packages Per Carton'].'</span></div>
				<div style="float:right;min-width:70px;text-align:center;"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div>
				<div style="float:right;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package'].'</span></div>
				 '),
				'stock'=>number(floor($data['Part Current Stock']))." $stock_status",


			);


		}
	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
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

function materials($_data, $db, $user, $account) {



	include_once 'utils/currency_functions.php';

	if ($user->get('User Type')=='Agent') {
		// $_data['parameters']['parent']=='supplier' and $_data['parameters']['parent_key']==$user->get('User Parent Key')
		if (!$_data['parameters']['parent']=='supplier') {
			echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
			exit;
		}else {
			$sql=sprintf('select count(*) as num from `Agent Supplier Bridge` where `Agent Supplier Agent Key`=%d and `Agent Supplier Supplier Key`=%d ',
				$user->get('User Parent Key'),
				$_data['parameters']['parent_key']
			);

			$ok=0;
			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					$ok=$row['num'];
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}
			if ($ok==0) {
				echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
				exit;
			}

		}


	}elseif (!$user->can_view('suppliers')) {
		echo json_encode(array('state'=>405, 'resp'=>'Forbidden'));
		exit;
	}



	$rtext_label='material';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();


	$exchange=-1;


	if ($result=$db->query($sql)) {



		foreach ($result as $data) {


			if ($exchange<0) {
				$exchange=currency_conversion($db, $data['Supplier Part Currency Code'], $account->get('Account Currency'), '- 1 day');
			}

			if ($exchange!=1) {

				$exchange_info=money(($data['Supplier Part Unit Cost']+$data['Supplier Part Unit Extra Cost']), $data['Supplier Part Currency Code']).' @'.$data['Supplier Part Currency Code'].'/'. $account->get('Account Currency').' '.sprintf('%.6f', $exchange);
			}else {
				$exchange_info='';
			}

			switch ($data['Supplier Part Status']) {
			case 'Available':
				$status=sprintf('<i class="fa fa-stop success" title="%s"></i>', _('Available'));
				break;
			case 'NoAvailable':
				$status=sprintf('<i class="fa fa-stop warning" title="%s"></i>', _('No available'));

				break;
			case 'Discontinued':
				$status=sprintf('<i class="fa fa-ban error" title="%s"></i>', _('Discontinued'));

				break;
			default:
				$status=$data['Supplier Part Status'];
				break;
			}

			switch ($data['Part Stock Status']) {
			case 'Surplus':
				$stock_status='<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Optimal':
				$stock_status='<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Low':
				$stock_status='<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Critical':
				$stock_status='<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
				break;
			case 'Out_Of_Stock':
				$stock_status='<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
				break;
			case 'Error':
				$stock_status='<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
				break;
			default:
				$stock_status=$data['Part Stock Status'];
				break;
			}

			if ($data['Part Status']=='Not In Use') {
				$part_status='<i class="fa fa-square-o fa-fw  very_discreet" aria-hidden="true"></i> ';

			}elseif ($data['Part Status']=='Discontinuing') {
				$part_status='<i class="fa fa-square fa-fw  very_discreet" aria-hidden="true"></i> ';

			}else {
				$part_status='<i class="fa fa-square fa-fw " aria-hidden="true"></i> ';
			}

			$part_description=$part_status.'<span style="min-width:80px;display: inline-block;" class="link padding_right_10" onClick="change_view(\'part/'.$data['Supplier Part Part SKU'].'\')">'.$data['Part Reference'].'</span> ';

			$adata[]=array(
				'id'=>(integer)$data['Supplier Part Key'],
				'supplier_key'=>(integer)$data['Supplier Part Supplier Key'],
				'supplier_code'=>$data['Supplier Code'],
				'part_key'=>(integer)$data['Supplier Part Part SKU'],
				'part_reference'=>$data['Part Reference'],
				'reference'=>$data['Supplier Part Reference'],
				'part_description'=>$part_description,


				'description'=>$data['Part Unit Description'],
				'status'=>$status,
				'cost'=>money($data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']),
				'delivered_cost'=>'<span title="'.$exchange_info.'">'.money($exchange*($data['Supplier Part Unit Cost']+$data['Supplier Part Unit Extra Cost']), $account->get('Account Currency')).'</span>',
				'packing'=>'
				 <div style="float:right;min-width:30px;;text-align:right" title="'._('Units per carton').'"><span class="discreet" >'.($data['Part Units Per Package']*$data['Supplier Part Packages Per Carton'].'</span></div>
				<div style="float:right;min-width:70px;text-align:center;"> <i  class="fa fa-arrow-right very_discreet padding_right_10 padding_left_10"></i><span>['.$data['Supplier Part Packages Per Carton'].']</span></div>
				<div style="float:right;min-width:20px;text-align:right"><span>'.$data['Part Units Per Package'].'</span></div>
				 '),
				'stock'=>number(floor($data['Part Current Stock']))." $stock_status",


			);


		}
	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
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
