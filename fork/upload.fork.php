<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 March 2016 at 12:33:52 GMT+8, Kuala Lumput, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function fork_upload($job) {






	include_once 'class.User.php';
	include_once 'class.Upload.php';
	include_once 'conf/object_fields.php';
	include_once 'utils/invalid_messages.php';
	include_once 'utils/object_functions.php';



	include_once 'external_libs/PHPExcel/Classes/PHPExcel.php';
	include_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';


	if (!$_data=get_fork_data($job)) {
		print "error reading fork data\n";
		return;

	}

	$fork_data=$_data['fork_data'];
	$fork_key=$_data['fork_key'];
	$inikoo_account_code=$_data['inikoo_account_code'];
	$db=$_data['db'];

	$account=new Account($db);


	$user=new User('id', $fork_data['user_key']);

	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Alias'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
	);

	$upload=new Upload('id', $fork_data['upload_key']);

	$upload->update(array('Upload State'=>'InProcess'));

	$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Start Date`=NOW() where `Fork Key`=%d ",
		$fork_key
	);
	$db->exec($sql);



	$fields=array();
	$fields_required=array();
	$field_index=0;
	$object_fields=get_object_fields($upload->get('Object'), $db);
	foreach ($object_fields as $field_group) {
		if (array_key_exists('fields', $field_group)) {
			foreach ($field_group['fields'] as $field) {



				if (array_key_exists('edit', $field)  and !array_key_exists('hidden', $field)     ) {

					$fields[]=preg_replace('/_/', ' ', $field['id']);

					if (!(array_key_exists('required', $field) and !$field['required'])) {
						$fields_required[$field_index]=$field_index;
					}
					$field_index++;
				}

			}
		}
	}


	$sql=sprintf("select `Upload Record Key`, uncompress(`Upload Record Data`) as data  from `Upload Record Dimension` where `Upload Record Upload Key`=%d and `Upload Record Status`='InProcess' ",
		$upload->id
	);


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {



			$sql=sprintf('select `Fork State` from `Fork Dimension` where `Fork Key`=%d', $fork_key);



			if ($result2=$db->query($sql)) {
				if ($row2 = $result2->fetch()) {
					if ($row2['Fork State']=='Cancelled') {

						$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s ,`Upload Record State`='Cancelled', `Upload Record Status`='Done'  where `Upload Record Upload Key`=%d and `Upload Record State`='InProcess' ",
							prepare_mysql(gmdate('Y-m-d H:i:s')),
							$upload->id
						);
						$db->exec($sql);
						update_stats($fork_key, $upload->id, $db);

						$sql=sprintf("update `Fork Dimension` set `Fork Finished Date`=NOW(),`Fork Cancelled Date`=NOW(),`Fork Result`=%s `Fork Operations Cancelled`=(`Fork Operations Total Operations`-`Fork Operations Done`-`Fork Operations No Changed`-`Fork Operations Errors`) where `Fork Key`=%d ",
							prepare_mysql('imported cancelled'),
							$fork_key
						);
						$db->exec($sql);

						$sql=sprintf("update `Upload Dimension` set `Upload State`='Cancelled' where `Upload Key`=%d ",
							$upload->id
						);
						$db->exec($sql);




						return false;
					}
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}






			$record_data=json_decode($row['data'], true);

			$fields_data='';
			$error=false;
			$error_required=array();
			foreach ($fields as $_key=>$_value) {

				$value=(array_key_exists($_key, $record_data)?$record_data[$_key]:'');

				if (array_key_exists($_key, $fields_required) and $value=='') {
					$error_required[]=$_value;
					$error=true;



				}

				$fields_data[$_value]=$value;

			}


			if ($error) {


				if (count($error_required)==1) {
					$error_code='missing_required_field';
					$error_metadata=json_encode($error_required);
				} elseif (count($error_required)>1) {
					$error_code='missing_required_fields';
					$error_metadata=json_encode($error_required);

				}



				$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s ,`Upload Record Message Code`=%s ,`Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`='Error' where `Upload Record Key`=%d ",
					prepare_mysql(gmdate('Y-m-d H:i:s')),
					prepare_mysql($error_code),
					prepare_mysql($error_metadata),
					$row['Upload Record Key']

				);
				$db->exec($sql);
				
				continue;

			}



			$_data=array(
				'parent'=>$upload->get('Parent'),
				'parent_key'=>$upload->get('Parent Key'),
				'object'=>$upload->get('Object'),
				'upload_record_key'=>$row['Upload Record Key'],

				'fields_data'=>$fields_data
			);


			$object_key=new_object($account, $db, $user, $editor, $_data);



			update_stats($fork_key, $upload->id, $db);


		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	update_stats($fork_key, $upload->id, $db);




	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Result`=%s where `Fork Key`=%d ",
		prepare_mysql('imported'),
		$fork_key
	);

	$db->exec($sql);

	$sql=sprintf("update `Upload Dimension` set  `Upload State`='Finished'  where `Upload Key`=%d ",
		$upload->id);
	$db->exec($sql);


}



function list_name_taken($list_name, $parent_key) {

	$sql=sprintf("select `List Key` from `List Dimension`  where `List Name`=%s and `List Parent Key`=%d ",
		prepare_mysql($list_name),
		$parent_key
	);

	$result=mysql_query($sql);
	$num_results= mysql_num_rows($result);



	return $num_results;

}


function new_object($account, $db, $user, $editor, $data) {




	$parent=get_object($data['parent'], $data['parent_key']);


	$editor['Date']=gmdate('Y-m-d H:i:s');

	$parent->editor=$editor;

	switch ($data['object']) {
	case 'Part':
		include_once 'class.Part.php';
		$object=$parent->create_part($data['fields_data']);

		if ($parent->new_part) {


		}else {


			$response=array(
				'state'=>400,
				'msg'=>$parent->msg

			);
			echo json_encode($response);
			exit;
		}
		break;
	case 'Manufacture_Task':
		include_once 'class.Manufacture_Task.php';
		$object=$parent->create_manufacture_task($data['fields_data']);

		if ($parent->new_manufacture_task) {


		}else {


			$response=array(
				'state'=>400,
				'msg'=>$parent->msg

			);
			echo json_encode($response);
			exit;
		}
		break;
	case 'User':
		include_once 'class.User.php';

		$parent->get_user_data();
		$object=$parent->create_user($data['fields_data']);






		break;
	case 'Customer':
		include_once 'class.Customer.php';
		$object=$parent->create_customer($data['fields_data']);

		break;
	case 'Supplier':
		include_once 'class.Supplier.php';
		$object=$parent->create_supplier($data['fields_data']);

		break;
	case 'Contractor':
		include_once 'class.Staff.php';

		$data['fields_data']['Staff Type']='Contractor';

		$object=$parent->create_staff($data['fields_data']);

		break;
	case 'Staff':
	case 'employee':
		include_once 'class.Staff.php';

		$object=$parent->create_staff($data['fields_data']);





		break;
	case 'API_Key':
		include_once 'class.API_Key.php';

		$object=$parent->create_api_key($data['fields_data']);
		if (!$parent->error) {

		}
		break;
	case 'Timesheet_Record':
		include_once 'class.Timesheet_Record.php';
		$object=$parent->create_timesheet_record($data['fields_data']);
		if (!$parent->error) {
			$updated_data=array(
				'Timesheet_Clocked_Hours'=>$parent->get('Clocked Hours')
			);
		}
		break;
	default:
		return false;

		break;
	}



	if ($parent->error) {

		$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s , `Upload Record Message Code`=%s , `Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`='Error' where `Upload Record Key`=%d ",
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql($parent->error_code),
			prepare_mysql($parent->error_metadata),
			$data['upload_record_key']

		);
		$db->exec($sql);
		return false;


	}else {

		$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s ,`Upload Record Message Code`='' ,`Upload Record Status`='Done' ,`Upload Record State`='OK',`Upload Record Object Key`=%d where `Upload Record Key`=%d ",
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			$object->id,
			$data['upload_record_key']
		);
		$db->exec($sql);
		return $object->id;
	}



}



function update_stats($fork_key, $upload_key, $db) {

	$elements=array('InProcess'=>0, 'OK'=>0, 'Error'=>0, 'Warning'=>0, 'Cancelled'=>0);

	$sql=sprintf('select `Upload Record State`,count(*) as num  from `Upload Record Dimension` where `Upload Record Upload Key`=%d group by `Upload Record State`', $upload_key);
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$elements[$row['Upload Record State']]=$row['num'];
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	$sql=sprintf('update `Fork Dimension` set `Fork Operations Done`=%d,`Fork Operations Errors`=%d ,`Fork Operations Cancelled`=%d where `Fork Key`=%d ',
		($elements['OK']+$elements['Warning']),
		$elements['Error'],
		$elements['Cancelled'],
		$fork_key);

	$db->exec($sql);

	$sql=sprintf('update `Upload Dimension` set `Upload OK`=%d,`Upload Errors`=%d ,`Upload Warnings`=%d where `Upload Key`=%d ',
		$elements['OK'],
		$elements['Error'],
		$elements['Warning'],
		$upload_key);
	$db->exec($sql);

}


?>
