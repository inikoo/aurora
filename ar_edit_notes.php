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
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),

		));
	delete_history($data);
	break;
case('edit_note'):
	$data=prepare_values($_REQUEST,array(
			'note_key'=>array('type'=>'key'),
			'record_index'=>array('type'=>'numeric'),

			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'note'=>array('type'=>'string'),
			'date'=>array('type'=>'string'),
		));
	edit_note($data);
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
case('add_attachment'):
	require_once 'class.Attachment.php';
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'caption'=>array('type'=>'string')
		));
	add_attachment($data);
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
	$db_field=get_parent_db_field($data);

	$subject->editor=$editor;
	if ( $data['note_type']=='deletable')
		$data['note_type']='Yes';
	else
		$data['note_type']='No';

	//print_r($data['note_type']);

	$subject->add_note($data['note'],$data['details'],false,$data['note_type']);


	$elements_numbers=array('Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0,'WebLog'=>0);
	$sql=sprintf("select count(*) as num , `Type` from  `%s History Bridge` where `%s Key`=%d group by `Type`",
		$db_field,
		$db_field,
		$data['parent_key']
	);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_numbers[$row['Type']]=number($row['num']);
	}

	if ($subject->updated) {
		$response= array('state'=>200,'newvalue'=>$subject->new_value,'key'=>'note','elements_numbers'=>$elements_numbers);

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
	$parent_key=$data['parent_key'];

	$db_field=get_parent_db_field($data);

	$sql=sprintf("select `Type` from `%s History Bridge` where `History Key`=%d and `Deletable`='Yes'",$db_field,$history_key);
	//print "$sql\n";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		if ($row['Type']=='Attachments') {
			include_once 'class.Attachment.php';
			$sql=sprintf("select `Attachment Bridge Key`,`Attachment Key` from `Attachment Bridge` where `Subject`='%s History Attachment' and `Subject Key`=%d",
				$db_field,
				$history_key
			);
			//print "$sql\n";
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {
				$sql=sprintf("delete from `Attachment Bridge` where `Attachment Bridge Key`=%d",$row2['Attachment Bridge Key']);
				mysql_query($sql);
				//print "$sql\n";
			}
			$attachment=new Attachment($row2['Attachment Key']);
			$attachment->delete();

		}
	}

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


	$elements_number=array('Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0,'WebLog'=>0);
	$sql=sprintf("select count(*) as num , `Type` from  `%s History Bridge` where `%s Key`=%d group by `Type`",
		$db_field,
		$db_field,
		$data['parent_key']
	);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number[$row['Type']]=number($row['num']);
	}


	$response=array('state'=>200,'action'=>$action,'msg'=>$msg,'elements_numbers'=>$elements_number);
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

function add_attachment($data) {
	global $editor;
	$subject=get_parent_object($data);
	$subject->editor=$editor;
	$db_field=get_parent_db_field($data);
	$msg='';
	$updated=false;


	if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
		$postMax = ini_get('post_max_size'); //grab the size limits...
		$msg= "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
		$response= array('state'=>400,'msg'=>_('Files could not be attached').".<br>".$msg,'key'=>'attach');
		echo base64_encode(json_encode($response));
		exit;

	}

	foreach ($_FILES as $file_data) {

		if ($file_data['size']==0) { 
		$msg= "This file seems that is empty, have a look and try again."; 
		$response= array('state'=>400,'msg'=>$msg,'key'=>'attach');
		echo base64_encode(json_encode($response));
		exit;

	}

		if ($file_data['error']) {
			$msg=$file_data['error'];
			if ($file_data['error']==4) {
				$msg=' '._('please choose a file, and try again');

			}
			$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
		echo base64_encode(json_encode($response));
			exit;
		}


		$_data=array(
			'Filename'=>$file_data['tmp_name'],
			'Attachment Caption'=>$data['caption'],
			'Attachment File Original Name'=>$file_data['name']
		);


		$subject->add_attachment($_data);

		if ($subject->updated) {
			$updated=$subject->updated;



		} else {
			$msg=$subject->msg;
		}
	}

	if ($updated) {
		$elements_numbers=array('Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0,'WebLog'=>0);

		$sql=sprintf("select count(*) as num , `Type` from  `%s History Bridge` where `%s Key`=%d group by `Type`",
			$db_field,
			$db_field,
			$data['parent_key']
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$elements_numbers[$row['Type']]=number($row['num']);
		}

		$response= array('state'=>200,'newvalue'=>1,'key'=>'attach','elements_numbers'=>$elements_numbers);

	} else {
		$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
	}

		echo base64_encode(json_encode($response));
}




?>
