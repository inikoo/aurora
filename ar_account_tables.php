<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 18:06:17 CET, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/object_functions.php';

if (!$user->can_view('account')) {
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
case 'data_sets':
	data_sets(get_table_parameters(), $db, $user, $account);
	break;
case 'timeseries':
	timeseries(get_table_parameters(), $db, $user, $account);
	break;
case 'timeserie_records':
	timeserie_records(get_table_parameters(), $db, $user, $account);
	break;
case 'images':
	images(get_table_parameters(), $db, $user, $account);
	break;
case 'attachments':
	attachments(get_table_parameters(), $db, $user, $account);
	break;
case 'uploads':
	uploads(get_table_parameters(), $db, $user, $account);
	break;
case 'materials':
	materials(get_table_parameters(), $db, $user, $account);
	break;
case 'upload_records':
	upload_records(get_table_parameters(), $db, $user, $account);
	break;
default:
	$response=array('state'=>405, 'resp'=>'Tipo not found '.$tipo);
	echo json_encode($response);
	exit;
	break;
}

function data_sets($_data, $db, $user, $account) {

	$rtext_label='data set';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			switch ($data['Data Sets Code']) {
			case 'Timeseries':
				$name=_('Timeseries');
				$request='account/data_sets/timeseries';
				break;
			case 'Images':
				$name=_('Images');
				$request='account/data_sets/images';
				break;
			case 'Attachments':
				$name=_('Attachments');
				$request='account/data_sets/attachments';
				break;
			case 'OSF':
				$name=_('Order transactions timeseries');
				$request='account/data_sets/osf';
				break;
			case 'ISF':
				$name=_('Inventory transactions timeseries');
				$request='account/data_sets/isf';
				break;
			case 'Uploads':
				$name=_('Uploads');
				$request='account/data_sets/uploads';
				break;
			case 'Materials':
				$name=_('Materials');
				$request='account/data_sets/materials';

				break;
			default:
				$name=$data['Data Sets Code'];
				$request='';
				break;
			}


			$adata[]=array(
				'id'=>(integer) $data['Data Sets Key'],
				'name'=>$name,
				'request'=>$request,
				'sets'=>number($data['Data Sets Number Sets']),
				'items'=>number($data['Data Sets Number Items']),
				'size'=>file_size($data['Data Sets Size']),
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


function timeseries($_data, $db, $user, $account) {

	$rtext_label='time serie';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			switch ($data['Timeseries Type']) {
			case 'StoreSales':

				$type=_('Store sales');
				$parent=$data['Store Code'];
				break;

			default:
				$type=$data['Timeseries Type'];
				$parent='';
				break;
			}


			$adata[]=array(
				'id'=>(integer) $data['Timeseries Key'],
				'formatted_id'=>sprintf('%04d', $data['Timeseries Key']),
				'type'=>$type,
				'parent'=>$parent,
				'records'=>number($data['Timeseries Number Records']),
				'from'=>strftime("%e %b %Y", strtotime($data['Timeseries From'].' +0:00')),
				'to'=>strftime("%e %b %Y", strtotime($data['Timeseries To'].' +0:00')),
				'last_updated'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Timeseries Updated'].' +0:00')),

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


function timeserie_records($_data, $db, $user, $account) {


	if ($_data['parameters']['frequency']=='annually') {
		$rtext_label='year';
	}elseif ($_data['parameters']['frequency']=='monthy') {
		$rtext_label='month';
	}elseif ($_data['parameters']['frequency']=='weekly') {
		$rtext_label='week';
	}elseif ($_data['parameters']['frequency']=='daily') {
		$rtext_label='day';
	}
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';
	include_once 'class.Timeseries.php';


	$timeseries=new Timeseries($_data['parameters']['parent_key']);

	$sql="select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {



		if ($timeseries->get('Type')=='StoreSales') {
			foreach ($result as $data) {

				if ($_data['parameters']['frequency']=='annually') {
					$date=strftime("%Y", strtotime($data['Timeseries Record Date'].' +0:00'));
				}elseif ($_data['parameters']['frequency']=='monthy') {
					$date=strftime("%b %Y", strtotime($data['Timeseries Record Date'].' +0:00'));
				}elseif ($_data['parameters']['frequency']=='weekly') {
					$date=strftime("(%e %b) %Y %W ", strtotime($data['Timeseries Record Date'].' +0:00'));
				}elseif ($_data['parameters']['frequency']=='daily') {
					$date=strftime("%a %e %b %Y", strtotime($data['Timeseries Record Date'].' +0:00'));
				}

				$adata[]=array(
					'float_a'=>money($data['Timeseries Record Float A'], $timeseries->parent->get('Currency Code')),
					'float_b'=>money($data['Timeseries Record Float B'], $account->get('Currency')),
					'int_a'=>number($data['Timeseries Record Integer A']),
					'int_b'=>number($data['Timeseries Record Integer B']),
					'date'=>$date

					//'date'=>strftime("%a %e %b %Y", strtotime($data['Timeseries Record Date'].' +0:00')),
					//'year'=>strftime("%Y", strtotime($data['Timeseries Record Date'].' +0:00')),
					//'month_year'=>strftime("%b %Y", strtotime($data['Timeseries Record Date'].' +0:00')),
					//'week_year'=>strftime("(%e %b) %Y %W ", strtotime($data['Timeseries Record Date'].' +0:00')),

				);
			}
		}else {

			foreach ($result as $data) {
				$adata[]=array(
					'float_a'=>$data['Timeseries Record Float A'],
					'float_b'=>$data['Timeseries Record Float B'],
					'float_c'=>$data['Timeseries Record Float C'],
					'float_f'=>$data['Timeseries Record Float D'],
					'integer_a'=>$data['Timeseries Record Integer A'],
					'integer_b'=>$data['Timeseries Record Integer B'],
					'date'=>strftime("%e %b %Y", strtotime($data['Timeseries Record Date'].' +0:00')),

				);
			}
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


function images($_data, $db, $user, $account) {

	$rtext_label='image';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {



			$adata[]=array(
				'id'=>(integer) $data['Image Key'],
				'formatted_id'=>sprintf('%06d', $data['Image Key']),
				'kind'=>$data['Image File Format'],
				'size'=>number($data['Image Width']).' x '.number($data['Image Height']),
				'filesize'=>file_size($data['Image File Size']),
				'thumbnail'=>sprintf('<img src="/image_root.php?id=%d&size=thumbnail">', $data['Image Key'])

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


function attachments($_data, $db, $user, $account) {

	$rtext_label='attachment';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			switch ($data['Attachment Type']) {
			case 'PDF':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-pdf-o"></i> %s', $data['Attachment MIME Type'], 'PDF');

				break;
			case 'Image':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-picture-o"></i> %s', $data['Attachment MIME Type'], _('Image'));
				break;
			case 'Compresed':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-archive-o"></i> %s', $data['Attachment MIME Type'], _('Compresed'));
				break;
			case 'Spreadsheet':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-table"></i> %s', $data['Attachment MIME Type'], _('Spreadsheet'));
				break;
			case 'Text':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-text-o"></i> %s', $data['Attachment MIME Type'], _('Text'));
				break;
			case 'Word':
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-word-o"></i> %s', $data['Attachment MIME Type'], 'Word');
				break;
			default:
				$file_type=sprintf('<i title="%s" class="fa fa-fw fa-file-o"></i> %s', $data['Attachment MIME Type'], _('Other'));
				break;
			}


			$adata[]=array(
				'id'=>(integer) $data['Attachment Key'],
				'formatted_id'=>sprintf('%04d', $data['Attachment Key']),
				'file_type'=>$file_type,
				'filesize'=>file_size($data['Attachment File Size']),
				'thumbnail'=>sprintf('<img src="/image_root.php?id=%d&size=thumbnail">', $data['Attachment Thumbnail Image Key'])

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


function uploads($_data, $db, $user, $account) {

	$rtext_label='upload';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {

			switch ($data['Upload Object']) {
			case 'supplier_part':
				$object=sprintf('<i  class="fa fa-fw fa-stop"></i> %s', _("Supplier's parts"));
				break;
			case 'supplier':
				$object=sprintf('<i  class="fa fa-fw fa-ship"></i> %s', _("Suppliers"));
				break;
			default:
				$object=$data['Upload Object'];
			}
			switch ($data['Upload State']) {
			case 'Uploaded':
				$state=_('Uploaded');
				break;
			case 'InProcess':
				$state=_('In process');
				break;
			case 'Finished':
				$state=_('Finished');
				break;
			case 'Cancelled':
				$state=_('Cancelled');
				break;
			default:
				$state=$data['Upload State'];
			}


			$adata[]=array(
				'id'=>(integer) $data['Upload Key'],
				'formatted_id'=>sprintf('%04d', $data['Upload Key']),
				'object'=>$object,
				'state'=>$state,
				'date'=>strftime("%a %e %b %Y", strtotime($data['Upload Date'].' +0:00')),
				'ok'=>number($data['Upload OK']),
				'records'=>number($data['Upload Records']),
				'warnings'=>number($data['Upload Warnings']),
				'errors'=>number($data['Upload Errors']),

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


function materials($_data, $db, $user, $account) {

	$rtext_label='material';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {


			switch ($data['Material Type']) {
			case 'Material':
				$type=  _('Material');
				break;
			case 'Ingredient':
				$type=  _('Ingredient');
				break;
			default:
				$type= $data['Material Type'];
				break;
			}

			$adata[]=array(
				'id'=>(integer) $data['Material Key'],
				'name'=>$data['Material Name'],
				'type'=>$type,
				'parts'=>number($data['Material Parts Number']),


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


function upload_records($_data, $db, $user, $account) {

	$rtext_label='record';
	include_once 'prepare_table/init.php';
	include_once 'utils/natural_language.php';

	$type=$upload->get('Upload Type');

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$adata=array();

	if ($result=$db->query($sql)) {

		foreach ($result as $data) {
			$object='';
			if ($type=='NewObjects') {

				switch ($data['Upload Record Status']) {
				case 'Done':

					switch ($data['Upload Record State']) {
					case 'OK':
						$state='<i class="fa fa-check success" aria-hidden="true"></i> '._('Created');
						$object=sprintf('<span class="button" onClick="change_view(\'%s\')">%s</span>', $data['link'], ($data['object_name']!=''?$data['object_name']:$data['object_auxiliar_name']));
						break;
					case 'Error':
						$state='<i class="fa fa-exclamation-circle error" aria-hidden="true" title="'._('Error').'"></i> ';
						switch ($data['Upload Record Message Code']) {
						case 'duplicated_field':
							$state.=_('Duplicated value in a unique field').' '.$data['Upload Record Message Metadata'];
							break;
						default:
							$state.=$data['Upload Record Message Code'];
							break;
						}

						$state.=sprintf('<span class="button" onClick="change_view(\'%s\')">%s</span>', $data['link'], ($data['object_name']!=''?$data['object_name']:$data['object_auxiliar_name']));
						break;
					default:
						$state=$data['Upload Record State'];
					}
					break;
				case 'InProcess':
					$state='<i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> '._('In process');
					break;

				}
			}else {


				switch ($data['Upload Record Status']) {
				case 'Done':

					$object=sprintf('<span class="button" onClick="change_view(\'%s\')">%s</span>', $data['link'], ($data['object_name']!=''?$data['object_name']:$data['object_auxiliar_name']));
					$state='<span >'.$data['Upload Record Message Metadata'].'</span>';

					switch ($data['Upload Record Message Code']) {
					case 'no_change':
						$state.=' <span class="very_discreet ">'._('Record not changed').'</span>';
						break;
					case 'updated':
						$state.=' <span class="success">'._('Updated').'</span>';
						break;
					case 'not_found':
						$state.=' <span class="error">'._('Record not found').'</span>';
						break;
					case 'object_not_found':
						$state.=' <span class="error"><i class="fa fa-exclamation-circle fa-fw" aria-hidden="true"></i> '._('Record not found').'</span>';
						break;	
					case 'skip':
						$state.=' <span class="warning"><i class="fa fa-share fa-fw" aria-hidden="true"></i> '._('Record skipped').'</span>';
						break;		
					default:
						$state.=$data['Upload Record Message Code'];
						break;
					}

					break;
				case 'InProcess':
					$state='<i class="fa fa-clock-o fa-fw" aria-hidden="true"></i> '._('In process');
					break;

				}



			}

			$adata[]=array(
				'id'=>(integer) $data['Upload Record Key'],
				'row'=>sprintf('%04d', $data['Upload Record Row Index']),
				'object'=>$object,
				'state'=>$state,


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
