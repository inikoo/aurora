<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/

require_once 'common.php';


require_once 'ar_edit_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}



$tipo=$_REQUEST['tipo'];
switch ($tipo) {



case('edit_sticky_note'):
	$data=prepare_values($_REQUEST,array(
			'note'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
		));

	edit_sticky_note($data);
	break;

case('delete_history'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),
			'parent'=>array('type'=>'string')

		));
	delete_history($data);
	break;
case('add_note'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'note'=>array('type'=>'string'),
			'details'=>array('type'=>'string'),
			'note_type'=>array('type'=>'string'),
		));
	add_note($data);
	break;

case('edit_note'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'note_key'=>array('type'=>'key'),
			'note'=>array('type'=>'string'),
			'date'=>array('type'=>'string'),
			'record_index'=>array('type'=>'string')
		));
	edit_note($data);
	break;
case('upload_attachment_to_subject'):
	upload_attachment_to_subject();
	break;

case('strikethrough_history'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),
						'parent'=>array('type'=>'string')

		));
	strikethrough_history($data);
	break;
case('unstrikethrough_history'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),
						'parent'=>array('type'=>'string')

		));
	unstrikethrough_history($data);
	break;

case('add_attachment'):
	$data=prepare_values($_REQUEST,array(
			'files_data'=>array('type'=>'json array'),
			'scope_key'=>array('type'=>'key'),
			'scope'=>array('type'=>'string'),
			'caption'=>array('type'=>'string')

		));
	add_attachment($data);
	break;
default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}


function edit_sticky_note($data) {
	global $editor;


	$subject=get_parent_object($data);
	$subject->editor=$editor;
	$subject->update_field_switcher('Sticky Note',$data['note']);

	$response= array('state'=>200,'newvalue'=>$subject->new_value,'key'=>'sticky_note');


	echo json_encode($response);

}

function add_note($data) {
	global $editor;

	$subject=get_parent_object($data);
//print_r($subject);
	$subject->editor=$editor;
	if ( $data['note_type']=='deletable')
		$data['note_type']='Yes';
	else
		$data['note_type']='No';

	//print_r($data['note_type']);

	$subject->add_note($data['note'],$data['details'],false,$data['note_type']);




	if ($subject->updated) {
		$response= array('state'=>200,'newvalue'=>$subject->new_value,'key'=>'note');

	} else {
		$response= array('state'=>400,'msg'=>$subject->msg,'key'=>'note');
	}
	echo json_encode($response);

}

function edit_note($data) {
	global $editor;

	$subject=get_parent_object($data);

	$subject->editor=$editor;





	$subject->edit_note($data['note_key'],$data['note'],'',$data['date']);




	if ($subject->updated) {
		$response= array('state'=>200,'newvalue'=>$subject->new_value,'key'=>'note','record_index'=>(float)$data['record_index']);

	} else {
		$response= array('state'=>400,'msg'=>$subject->msg,'key'=>'note');
	}
	echo json_encode($response);

}


function delete_history($data) {

	$history_key=$data['key'];
	$db_field=get_parent_db_field($data);

	$sql=sprintf("delete from `%s History Bridge` where `History Key`=%d and `Deletable`='Yes'",$db_field,$history_key);

	mysql_query($sql);
	if (mysql_affected_rows()) {
		$sql=sprintf("delete from `History Dimension` where `History Key`=%d",$history_key);
		mysql_query($sql);
		$action='deleted';
		$msg=_('History record Deleted');
	} else {
		$action='no_change';
		$msg='Record can not be deleted';
	}
	$response=array('state'=>200,'action'=>$action,'msg'=>$msg);
	echo json_encode($response);
}


function strikethrough_history($data) {
	$history_key=$data['key'];
	$db_field=get_parent_db_field($data);

	$sql=sprintf("update `%s History Bridge` set  `Strikethrough`='Yes'   where `History Key`=%d ",$db_field,$history_key);
	mysql_query($sql);
	//  print $sql;
	$response=array('state'=>200,'strikethrough'=>'Yes','delete'=>'<img alt="'._('unstrikethrough').'" src="art/icons/text_unstrikethrough.png" />');
	echo json_encode($response);
}
function unstrikethrough_history($data) {
	$history_key=$data['key'];
	$db_field=get_parent_db_field($data);

	$sql=sprintf("update `%s History Bridge` set  `Strikethrough`='No'  where `History Key`=%d ",$db_field,$history_key);
	mysql_query($sql);
	$response=array('state'=>200,'strikethrough'=>'No','delete'=>'<img alt="'._('strikethrough').'" src="art/icons/text_strikethrough.png" />');
	echo json_encode($response);
}


function add_attachment($data) {

	if ($data['scope']=='customer') {
		return add_attachment_to_subject_history($data);
	}

}

function add_attachment_to_subject_history($data) {
	global $editor;
	$customer=new Customer($data['scope_key']);
	$customer->editor=$editor;
	$msg=
		$updated=false;
	foreach ($data['files_data'] as $file_data) {
		$_data=array(
			'Filename'=>$file_data['filename_with_path'],
			'Attachment Caption'=>$data['caption'],
			'Attachment MIME Type'=>$file_data['type'],
			'Attachment File Original Name'=>$file_data['original_filename']
		);
		$customer->add_attachment($_data);
		if ($customer->updated) {
			$updated=$customer->updated;
		} else {
			$msg=$customer->msg;
		}



	}

	if ($updated) {
		$response= array('state'=>200,'newvalue'=>1,'key'=>'attach');

	} else {
		$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
	}

	echo json_encode($response);
}




function upload_attachment_to_subject() {
	global $editor;
	if (isset($_FILES['attach']['tmp_name'])) {


		//print_r($_FILES['attach']);
		//print_r($_REQUEST);
		// return;
		$file_data=$_FILES['attach'];
		$caption=$_REQUEST['caption'];
		$customer_key=$_REQUEST['attach_customer_key'];

		$customer=new Customer($customer_key);
		$customer->editor=$editor;

		$updated=false;

		$_data=array(
			'Filename'=>$file_data['tmp_name'],
			'Attachment Caption'=>$caption,
			'Attachment MIME Type'=>$file_data['type'],
			'Attachment File Original Name'=>$file_data['name']
		);
		$customer->add_attachment($_data);
		if ($customer->updated) {
			$updated=$customer->updated;
		} else {
			$msg=$customer->msg;
		}




	}


	if ($updated) {
		$response= array('state'=>200,'newvalue'=>1,'key'=>'attach');

	} else {
		$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
	}

	echo json_encode($response);

}


function get_parent_db_field($data) {
	switch ($data['parent']) {
	case 'customer':
		$db_field='Customer';
		break;
	case 'store':
		$db_field='Store';
		break;
	case 'department':
		$db_field='Department';
		break;
	case 'family':
		$db_field='Family';
		break;
	case 'product':
		$db_field='Product';
		break;
	case 'part':
		$db_field='Part';
		break;
	case 'supplier':
		$db_field='Supplier';
		break;
	case 'supplierproduct':
		$db_field='Supplier Product';
		break;
	default:
		$response=array('state'=>400,'msg'=>'Non acceptable request wo (t)');
		echo json_encode($response);
		exit;
	}

	return $db_field;


}

function get_parent_object($data) {

	switch ($data['parent']) {
	case 'customer':
		include_once 'class.Customer.php';
		$subject=new Customer($data['parent_key']);
		break;
	case 'store':
		include_once 'class.Store.php';
		$subject=new Store($data['parent_key']);
		break;
	case 'department':
		include_once 'class.Department.php';
		$subject=new Department($data['parent_key']);
		break;
	case 'family':
		include_once 'class.Family.php';
		$subject=new Family($data['parent_key']);
		break;
	case 'product':
		include_once 'class.Product.php';
		$subject=new Product('pid',$data['parent_key']);
		break;
	case 'part':
		include_once 'class.Part.php';
		$subject=new Part($data['parent_key']);
		break;
	case 'supplier':
		include_once 'class.Supplier.php';
		$subject=new Supplier($data['parent_key']);
		break;
	case 'supplierproduct':
		include_once 'class.SupplierProduct.php';
		$subject=new SupplierProduct($data['parent_key']);
		break;
	default:
		$response=array('state'=>400,'msg'=>'Non acceptable request wo (t)');
		echo json_encode($response);
		exit;
	}

	return $subject;


}

?>
