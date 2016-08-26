<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 19:05:00 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function fork_upload_edit($job) {


	include_once 'class.User.php';
	include_once 'class.Upload.php';
	include_once 'conf/export_edit_template_fields.php';
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


	switch ($upload->get('Upload Object')) {
	case 'supplier_parts':
		include_once 'class.SupplierPart.php';
		$valid_keys=array('Supplier Part Key');
		$valid_fields=$export_edit_template_fields['supplier_parts'];
		$object_name='supplier_part';
		break;
	default:

		break;
	}


	$upload_metadata=$upload->get('Metadata');


	$fields=$upload_metadata['fields'];

	$key_index=-1;
	$valid_indexes=array();
	//print_r($fields);
	foreach ($fields as $key=>$value) {
		$value=_trim($value);
		if (preg_match('/^Id\s*:\s*(.+)$/', _trim($value), $matches)) {
			if (in_array($matches[1], $valid_keys)) {
				$key_index=$key;
			}
		}else {
			//print $value;

			if (in_array($value, array_column($valid_fields, 'field'))) {
				$valid_indexes[$key]=$value;
			}

		}
	}



	if ($key_index<0) {
		$error_code='missing_required_field';
		$error_metadata=json_encode(array());

		$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s ,`Upload Record State`='Error', `Upload Record Status`='Done'  where `Upload Record Upload Key`=%d and `Upload Record State`='InProcess' ",
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			$upload->id
		);
		$db->exec($sql);
		update_upload_edit_stats($fork_key, $upload->id, $db);

		$sql=sprintf("update `Fork Dimension` set `Fork Finished Date`=NOW(),`Fork Result`=%s `Fork Operations Errors`=(`Fork Operations Total Operations`-`Fork Operations Done`-`Fork Operations No Changed`-`Fork Operations Errors`) where `Fork Key`=%d ",
			prepare_mysql('error'),
			$fork_key
		);
		$db->exec($sql);

		$sql=sprintf("update `Upload Dimension` set `Upload State`='Finished' where `Upload Key`=%d ",
			$upload->id
		);
		$db->exec($sql);




		return false;

	}









	//---


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
						update_upload_edit_stats($fork_key, $upload->id, $db);

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

			//print_r($record_data);


			if ($record_data[$key_index]=='') {

				$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s ,`Upload Record Message Code`=%s ,`Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`=%s  where `Upload Record Key`=%d ",
					prepare_mysql(gmdate('Y-m-d H:i:s')),
					prepare_mysql('skip'),
					prepare_mysql(''),
					prepare_mysql('Warning'),
					$row['Upload Record Key']
				);
				$db->exec($sql);
				update_upload_edit_stats($fork_key, $upload->id, $db);
				continue;

			}

			$object=get_object($object_name, $record_data[$key_index]);

			if (!$object->id) {

				$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s ,`Upload Record Message Code`=%s ,`Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`=%s  where `Upload Record Key`=%d ",
					prepare_mysql(gmdate('Y-m-d H:i:s')),
					prepare_mysql('object_not_found'),
					prepare_mysql(''),
					prepare_mysql('Error'),
					$row['Upload Record Key']
				);
				$db->exec($sql);
				update_upload_edit_stats($fork_key, $upload->id, $db);
				continue;

			}


			$edit_data=array();


			$row_results=array();

			$errors=0;
			$updated_records=0;
			$msg='';
			$message_code='';

			//print_r($object->data);

			//print $object->id;

			foreach ($valid_indexes as $index=>$field) {
				$object->update(array($field=>$record_data[$index]));




				if ($object->updated) {
					//print "$field ".$record_data[$index]." ";
					//print "updated\n";
					$msg.='<i class="fa fa-check success fa-fw" aria-hidden="true" title="'.$field.'"></i>, ';
					$updated_records++;
				}elseif ($object->error) {
					//print "$field ".$record_data[$index]." ";
					//print "error ".$object->msg."\n";

					$msg.='<span class="error"><i class="fa fa-exclamation-circle fa-fw" aria-hidden="true" title="'.$field.'"></i> '.$object->msg.'</span>, ';
					$errors++;
				}else {
					//print "$field ".$record_data[$index]." ";
					//print "nochange \n";
					$msg.='<i class="fa fa-minus very_discreet fa-fw" aria-hidden="true" title="'.$field.'"></i>, ';
				}


			}
			// print "\n";
			$msg=preg_replace('/, $/', '', $msg);




			if ($errors) {
				// exit();
				if ($updated_records==0) {
					$record_state='Error';
				}else {
					$record_state='Warning';
				}

			}else {
				$record_state='OK';
			}

			if ($errors==0 and $updated_records==0) {
				$message_code='no_change';
				$record_state='NoChange';
			}else {
				//exit();
				if ($updated_records and $errors==0) {
					$message_code='updated';
				}

			}



			$sql=sprintf("update `Upload Record Dimension` set `Upload Record Date`=%s ,`Upload Record Message Code`=%s ,`Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`=%s,`Upload Record Object Key`=%d where `Upload Record Key`=%d ",
				prepare_mysql(gmdate('Y-m-d H:i:s')),
				prepare_mysql($message_code),
				prepare_mysql($msg),
				prepare_mysql($record_state),
				$object->id,
				$row['Upload Record Key']

			);

			//print "$sql\n";
			$db->exec($sql);



			update_upload_edit_stats($fork_key, $upload->id, $db);


		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	update_upload_edit_stats($fork_key, $upload->id, $db);




	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Result`=%s where `Fork Key`=%d ",
		prepare_mysql('imported'),
		$fork_key
	);

	$db->exec($sql);

	$sql=sprintf("update `Upload Dimension` set  `Upload State`='Finished'  where `Upload Key`=%d ",
		$upload->id);
	$db->exec($sql);


}




function update_upload_edit_stats($fork_key, $upload_key, $db) {

	$elements=array('InProcess'=>0, 'OK'=>0, 'Error'=>0, 'Warning'=>0, 'Cancelled'=>0,'NoChange'=>0);

	$sql=sprintf('select `Upload Record State`,count(*) as num  from `Upload Record Dimension` where `Upload Record Upload Key`=%d group by `Upload Record State`', $upload_key);
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$elements[$row['Upload Record State']]=$row['num'];
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	$sql=sprintf('update `Fork Dimension` set `Fork Operations Done`=%d,`Fork Operations No Changed`=%d,  `Fork Operations Errors`=%d ,`Fork Operations Cancelled`=%d where `Fork Key`=%d ',
		($elements['OK']+$elements['Warning']),
		$elements['NoChange'],
		$elements['Error'],
		$elements['Cancelled'],
		$fork_key);

	$db->exec($sql);

	$sql=sprintf('update `Upload Dimension` set `Upload OK`=%d,`Upload No Change`=%d,`Upload Errors`=%d ,`Upload Warnings`=%d where `Upload Key`=%d ',
		$elements['OK'],
		$elements['NoChange'],
		$elements['Error'],
		$elements['Warning'],
		$upload_key);
	$db->exec($sql);

}


?>
