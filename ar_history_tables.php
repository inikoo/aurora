<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2009
 Refurbished: 6 October 2015 at 09:41:00 BST, Sheffield UK
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
case 'object_history':
	object_history(get_table_parameters(), $db, $user);
	break;

default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}


function object_history($_data, $db, $user) {

   
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	


	foreach ($db->query($sql) as $data) {
	if ($data['History Details']=='')
			$note=$data['History Abstract'];
		else
			$note=$data['History Abstract'].' <img class="button" d="no" id="ch'.$data['History Key'].'" hid="'.$data['History Key'].'" onClick="showdetails(this)" src="/art/icons/closed.png" alt="Show details" />';

		//$objeto=$data['Direct Object'];
		$objeto=$data['History Details'];

		if ($data['Subject']=='Customer')
			$author=_('Customer');
		else
			$author=$data['Author Name'];


		//$delete=($data['Type']=='Notes'?($data['Deletable']=='Yes'?'<img alt="'._('delete').'" src="art/icons/cross.png" />':($data['Strikethrough']=='Yes'?'<img alt="'._('unstrikethrough').'" src="art/icons/text_unstrikethrough.png" />':'<img alt="'._('strikethrough').'" src="art/icons/text_strikethrough.png" />')):'');


		$delete='';
		if ($data['Type']=='Notes') {
			if ($data['Deletable']=='Yes') {
				$delete='<img alt="'._('delete').'" src="/art/icons/cross.png" />';
			}
			else {
				if ($data['Strikethrough']=='Yes') {
					$delete='<img alt="'._('unstrikethrough').'" src="/art/icons/text_unstrikethrough.png" />';
				}else {
					$delete='<img alt="'._('strikethrough').'" src="/art/icons/text_strikethrough.png" />';
				}
			}

		}elseif ($data['Type']=='Attachments') {
			if ($data['Deletable']=='Yes') {
				$delete='<img alt="'._('delete').'" src="art/icons/cross.png" />';
			}
		}

		$edit=(($data['Deletable']=='Yes' or $data['Type']=='Orders')?'<img style="cursor:pointer" alt="'._('edit').'" src="/art/icons/edit.gif" />':'');



		if ($data['Type']=='Attachments') {
			$edit='';

		}elseif ($data['Deletable']=='Yes' or $data['Type']=='Orders') {
			$edit='<img style="cursor:pointer" alt="'._('edit').'" src="/art/icons/edit.gif" />';
		}else {
			$edit='';
		}

		$adata[]=array(
			'id'=>(integer) $data['History Key'],
			'date'=>$data['History Date'],
			'date'=>strftime("%a %e %b %Y %H:%M %Z ", strtotime($data['History Date']." +00:00")),
			'time'=>strftime("%H:%M %Z", strtotime($data['History Date']." +00:00")),
			'objeto'=>$objeto,
			'note'=>$note,
			'author'=>$author,
			'delete'=>$delete,
			'edit'=>$edit,
			'can_delete'=>($data['Deletable']=='Yes'?1:0),
			'delete_type'=>_('delete'),
			'type'=>$data['Type'],
			'strikethrough'=>$data['Strikethrough']

		);

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


function departments($_data, $db, $user) {
	$rtext_label='department';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;


	foreach ($db->query($sql) as $data) {


		$adata[]=array(
			'id'=>(integer) $data['Product Department Key'],
			'store_key'=>(integer) $data['Product Department Store Key'],
			'code'=>$data['Product Department Code'],
			'name'=>$data['Product Department Name'],

		);

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


function families($_data, $db, $user) {
	$rtext_label='family';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;

	foreach ($db->query($sql) as $data) {
		$adata[]=array(
			'id'=>(integer) $data['Product Family Key'],
			'code'=>$data['Product Family Code'],
			'name'=>$data['Product Family Name'],
		);

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


function products($_data, $db, $user) {
	$rtext_label='product';
	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	$adata=array();

	// print $sql;


	foreach ($db->query($sql) as $data) {


		$adata[]=array(

			'id'=>(integer) $data['Product ID'],
			'code'=>$data['Product Code'],
			'name'=>$data['Product Name'],
		);

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
